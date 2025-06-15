<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Period;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\MrpController;

class OrderController extends Controller
{
    public function index()
    {
        return view('menu.order.index', [
            'records'  => Order::all(),
            'resource' => 'orders'
        ]);
    }

    public function create()
    {
        return view('menu.order.form', [
            'action'    => 'create',
            'resource'  => 'orders',
            'record'    => new Order,
            'customers' => Customer::all(),
            'periods'   => Period::all(),
            'items'     => Item::all(),
        ]);
    }

    public function store(Request $request)
    {
        $exists = Order::where('order_id', $request->order_id)->exists();
        if ($exists) {
            return redirect()->back()
                             ->with('error', 'Order sudah ada.')
                             ->withInput();
        }

        Order::create($request->all());

        // Rerun MRP setelah tambah order
        app(MrpController::class)->processMRP();

        return redirect()->route('orders.index')
                         ->with('success', 'Order berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('menu.order.form', [
            'action'   => 'edit',
            'resource' => 'orders',
            'record'   => Order::findOrFail($id),
            'customers' => Customer::all(),
            'periods'   => Period::all(),
            'items'     => Item::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());

        // Rerun MRP setelah update order
        app(MrpController::class)->processMRP();

        return redirect()->route('menu.order.index')
                         ->with('success', 'Order berhasil diupdate.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Hapus order
        $order->delete();

        // Rerun MRP setelah hapus order
        app(MrpController::class)->processMRP();

        return redirect()->route('orders.index')
                         ->with('success', 'Order berhasil dihapus dan MRP telah dihitung ulang.');
    }
}
