<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Bom;
use App\Models\Item;
use App\Models\Period;
use App\Models\ItemPeriod;
use Illuminate\Support\Collection;

class MrpController extends Controller
{
    public function processMRP()
    {
        $orders = Order::all();
        $periods = Period::orderBy('period_number')->pluck('period_number');
        $items = Item::all();

        // Step 1: Ensure item_period rows exist
        foreach ($items as $item) {
            foreach ($periods as $p) {
                ItemPeriod::firstOrCreate(
                    ['item_id' => $item->item_id, 'period_number' => $p],
                    [
                        'gross_requirement'     => 0,
                        'projected_inventory'   => 0,
                        'planned_order_receipt' => 0,
                        'planned_order_release' => 0,
                    ]
                );
            }
        }

        // Step 2: Reset all fields
        ItemPeriod::query()->update([
            'gross_requirement'     => 0,
            'projected_inventory'   => 0,
            'planned_order_receipt' => 0,
            'planned_order_release' => 0,
        ]);

        if ($orders->isEmpty()) {
            return;
        }

        // Step 3: Recursive gross requirement calculation
        $allReq = new Collection;
        foreach ($orders as $order) {
            $this->gatherRequirements($order->item_id, (string)$order->quantity, $order->period_number, $allReq);
        }

        // Step 4: Aggregate and update gross requirements
        $allReq->groupBy(fn($r) => "{$r['item_id']}-{$r['due']}")
            ->each(function ($group, $key) {
                [$itemId, $period] = explode('-', $key);
                $sumQty = $group->reduce(fn($carry, $row) => bcadd($carry, $row['qty'], 6), '0');
                ItemPeriod::where('item_id', $itemId)
                    ->where('period_number', $period)
                    ->update(['gross_requirement' => $sumQty]);
            });

        // Step 5: MRP calculations
        foreach ($items as $item) {
            $LT = $item->lead_time;
            $LS = $item->lot_size;

            if ($periods->contains(0)) {
                ItemPeriod::where('item_id', $item->item_id)
                    ->where('period_number', 0)
                    ->update(['projected_inventory' => $item->inventory]);
            }

            foreach ($periods as $p) {
                if ($p === 0) continue;

                $row = ItemPeriod::where('item_id', $item->item_id)
                    ->where('period_number', $p)
                    ->first();

                $prevInv = ItemPeriod::where('item_id', $item->item_id)
                        ->where('period_number', $p - 1)
                        ->value('projected_inventory') ?? 0;

                $g = $row->gross_requirement;
                $net = bcsub($g, $prevInv, 6);

                if (bccomp($net, '0', 6) === 1) {
                    $quot = bcdiv($net, $LS, 6);
                    $round = ceil((float)$quot);
                    $inc = bcmul((string)$round, $LS, 6);

                    $rel = max($periods->first(), $p - $LT);
                    ItemPeriod::where('item_id', $item->item_id)
                        ->where('period_number', $rel)
                        ->increment('planned_order_release', $inc);

                    $projInv = bcsub(bcadd($prevInv, $inc, 6), $g, 6);
                    ItemPeriod::where('item_id', $item->item_id)
                        ->where('period_number', $p)
                        ->update([
                            'planned_order_receipt' => $inc,
                            'projected_inventory'   => max(0, (float)$projInv),
                        ]);
                } else {
                    $projInv = bcsub($prevInv, $g, 6);
                    ItemPeriod::where('item_id', $item->item_id)
                        ->where('period_number', $p)
                        ->update([
                            'projected_inventory' => max(0, (float)$projInv),
                        ]);
                }
            }
        }
    }

    private function gatherRequirements(string $itemId, string $qty, int $due, Collection &$out)
    {
        $item = Item::findOrFail($itemId);
        $out->push(['item_id' => $itemId, 'qty' => $qty, 'due' => $due]);

        $parentLt = $item->lead_time;

        Bom::where('item_id', $itemId)->get()->each(function ($bom) use ($item, $qty, $due, &$out, $parentLt) {
            $childItem = Item::findOrFail($bom->component_id);
            $childDue = max(0, $due - $item->lead_time);

            $strQty = (string) $qty;
            $strMultiplier = (string) $bom->bom_multiplier;

            if ($childItem->tipe === 'statis') {
                $childQty = $strMultiplier;
            } else {
                $childQty = bcmul($strQty, $strMultiplier, 6);
            }

            $this->gatherRequirements($bom->component_id, $childQty, $childDue, $out);
        });
    }
}
