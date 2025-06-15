<?php

namespace App\Http\Controllers;

use App\Models\ItemPeriod;
USE DB;
use Illuminate\Http\Request;

class ItemPeriodController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort');
        $resource = 'item_periods';

        if ($sort === 'item_id') {
            $itemPeriods = ItemPeriod::with('item')
                ->get()
                ->sortBy(function ($ip) {
                    return $ip->item->level ?? PHP_INT_MAX;
                })
                ->groupBy('item_id');
        } elseif ($sort === 'period_number') {
            $itemPeriods = ItemPeriod::with('item')
                ->get()
                ->groupBy('period_number')
                ->map(function ($group) {
                    return $group->sortBy(function ($ip) {
                        return $ip->item->level ?? PHP_INT_MAX;
                    });
                });
        } else {
            $itemPeriods = ItemPeriod::with('item')->get();
        }

        return view('menu.itemPeriod.index', [
            'records' => $itemPeriods,
            'resource' => $resource,
        ]);
    }

    public function create() {
        return view('menu.itemPeriod.form', ['action' => 'create', 'resource' => 'item_periods', 'record' => new ItemPeriod]);
    }

    public function store(Request $request) {
        $exists = ItemPeriod::where('item_id', $request->item_id)
                             ->where('period_number', $request->period_number)
                             ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Item Period sudah ada.')->withInput();
        }
        ItemPeriod::create($request->all());
        return redirect()->route('item_periods.index')->with('success', 'Item Period berhasil ditambahkan.');
    }

    public function edit($item_id, $period_number) {
        $record = ItemPeriod::where('item_id', $item_id)->where('period_number', $period_number)->firstOrFail();
        return view('menu.itemPeriod.form', ['action' => 'edit', 'resource' => 'item_periods', 'record' => $record]);
    }

    public function update(Request $request, $item_id, $period_number) {
        $record = ItemPeriod::where('item_id', $item_id)->where('period_number', $period_number)->firstOrFail();
        $record->update($request->all());
        return redirect()->route('item_periods.index')->with('success', 'Item Period berhasil diupdate.');
    }

    public function destroy($item_id, $period_number) {
        ItemPeriod::where('item_id', $item_id)->where('period_number', $period_number)->delete();
        return redirect()->route('item_periods.index')->with('success', 'Item Period berhasil dihapus.');
    }
}