<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('unit.index', compact('units'));
    }

    public function create()
    {
        $units = Unit::all();
        return view('unit.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->request->add(['createdBy' => auth()->user()->email]);
        Unit::create($request->all());
        $request->session()->flash('message', 'Unit created Successfully!');
        return to_route('unit.index');
    }

    public function show(Unit $unit)
    {
        return view('unit.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $units = Unit::all();
        return view('unit.edit', compact('unit', 'units'));

    }

    public function update(Request $request, Unit $unit)
    {
        $unit->update($request->all());
        $request->session()->flash('message', 'Unit Updated Successfully!');
        return to_route('unit.index');
    }

    public function destroy(Request $request, Unit $unit)
    {
        $unit->delete();
        $request->session()->flash('error', 'Unit Deleted Successfully!');
        return to_route('unit.index');
    }

    public function getValue($id)
    {
        $unit = Unit::find($id);
        return $unit->value;
    }
}
