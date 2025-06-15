<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use Illuminate\Http\Request;

class BomController extends Controller
{
    public function index()
    {
        // Eager load relasi 'item' dan 'component'
        $boms = Bom::with(['item', 'component'])
                ->orderBy('level')
                ->orderBy('item_id')
                ->orderBy('component_id')
                ->get()
                ->groupBy('item_id');

        return view('menu.bom.index', [
            'resource' => 'boms',
            'records' => $boms,
        ]);
    }

    public function create()
    {
        // Ambil data item untuk dropdown
        $items = \App\Models\Item::all();

        return view('menu.bom.form', [
            'action'   => 'create',
            'resource' => 'boms',
            'record'   => new Bom(), // kosong untuk form
            'items'    => $items,    // data item untuk dropdown
        ]);
    }

    public function store(Request $request)
    {
        // Validasi sederhana
        $request->validate([
            'item_id' => 'required|string',
            'component_id' => 'required|string',
            // Tambah validasi field lain kalau perlu
        ]);

        Bom::create($request->all());

        return redirect()->route('boms.index')->with('success', 'BOM created successfully.');
    }

    public function edit($parent_id, $child_id)
    {
        $bom = Bom::findByItemAndComponent($parent_id, $child_id);
        $items = \App\Models\Item::all();
        
        return view('menu.bom.form', [
            'action' => 'edit',
            'resource' => 'boms',
            'record' => $bom,
            'items' => $items,
        ]);
    }

    public function update(Request $request, $parent_id, $child_id)
    {
        $bom = Bom::findByItemAndComponent($parent_id, $child_id);

        $bom->update($request->all());

        return redirect()->route('boms.index')->with('success', 'BOM updated successfully.');
    }

    public function destroy($parent_id, $child_id)
    {
        $bom = Bom::findByItemAndComponent($parent_id, $child_id);

        
        if ($bom) {
            Bom::where('item_id', $parent_id)
                ->where('component_id', $child_id)
                ->delete();
        }

        return redirect()->route('boms.index')->with('success', 'BOM deleted successfully.');
    }
}
