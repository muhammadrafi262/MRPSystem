<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index() {
        $items = Item::orderBy('level')->get();

        return view('menu.item.index', [
            'records' => $items,
            'resource' => 'items',
        ]);
    }

    public function create() {
        return view('menu.item.form', ['action' => 'create', 'resource' => 'items', 'record' => new Item]);
    }

    public function store(Request $request) {
        $exists = Item::where('item_id', $request->item_id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Item sudah ada.')->withInput();
        }
        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit($id) {
        return view('menu.item.form', ['action' => 'edit', 'resource' => 'items', 'record' => Item::findOrFail($id)]);
    }

    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item berhasil diupdate.');
    }

    public function destroy($id) {
        Item::destroy($id);
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }
}