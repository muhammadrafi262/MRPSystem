<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {
        return view('menu.customer.index', ['records' => Customer::all(), 'resource' => 'customers']);
    }

    public function create() {
        return view('menu.customer.form', ['action' => 'create', 'resource' => 'customers', 'record' => new Customer]);
    }

    public function store(Request $request) {
        $exists = Customer::where('customer_id', $request->customer_id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Customer sudah ada.')->withInput();
        }
        Customer::create($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit($id) {
        return view('menu.customer.form', ['action' => 'edit', 'resource' => 'customers', 'record' => Customer::findOrFail($id)]);
    }

    public function update(Request $request, $id) {
        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer berhasil diupdate.');
    }

    public function destroy($id) {
        Customer::destroy($id);
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}