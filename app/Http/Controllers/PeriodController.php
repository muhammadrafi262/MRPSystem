<?php

namespace App\Http\Controllers;

use App\Models\Period;
use DB;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index() {
        return view('menu.period.index', ['records' => Period::all(), 'resource' => 'periods']);
    }

    public function create() {
        return view('menu.period.form', ['action' => 'create', 'resource' => 'periods', 'record' => new Period]);
    }

    public function store(Request $request) {
        $exists = Period::where('period_number', $request->period_number)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Period sudah ada.')->withInput();
        }
        Period::create($request->all());
        return redirect()->route('periods.index')->with('success', 'Period berhasil ditambahkan.');
    }

    public function edit($id) {
        return view('menu.period.form', ['action' => 'edit', 'resource' => 'periods', 'record' => Period::findOrFail($id)]);
    }

    public function update(Request $request, $id) {
        $period = Period::findOrFail($id);
        $period->update($request->all());
        return redirect()->route('periods.index')->with('success', 'Period berhasil diupdate.');
    }

    public function destroy($id) {
        Period::destroy($id);
        return redirect()->route('periods.index')->with('success', 'Period berhasil dihapus.');
    }
}
