<?php

namespace App\Http\Controllers;

use App\Models\RiceItem;
use Illuminate\Http\Request;

class RiceItemController extends Controller
{
    public function index()
    {
        $riceItems = RiceItem::latest()->paginate(10);
        return view('rice-items.index', compact('riceItems'));
    }

    public function create()
    {
        return view('rice-items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price_per_kg'   => 'required|numeric|min:0',
            'stock_quantity' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
        ]);

        RiceItem::create($validated);

        return redirect()->route('rice-items.index')
            ->with('success', 'Rice item added successfully.');
    }

    public function show(RiceItem $riceItem)
    {
        return view('rice-items.show', compact('riceItem'));
    }

    public function edit(RiceItem $riceItem)
    {
        return view('rice-items.edit', compact('riceItem'));
    }

    public function update(Request $request, RiceItem $riceItem)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price_per_kg'   => 'required|numeric|min:0',
            'stock_quantity' => 'required|numeric|min:0',
            'description'    => 'nullable|string',
        ]);

        $riceItem->update($validated);

        return redirect()->route('rice-items.index')
            ->with('success', 'Rice item updated successfully.');
    }

    public function destroy(RiceItem $riceItem)
    {
        $riceItem->delete();

        return redirect()->route('rice-items.index')
            ->with('success', 'Rice item deleted successfully.');
    }
}
