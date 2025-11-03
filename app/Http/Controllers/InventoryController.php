<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Inventory;

class InventoryController extends Controller
{
    // Display all inventory items
    public function index()
    {
        $inventory = Inventory::paginate(20); // Paginate with 20 items per page
        $item = null; // Default value to avoid undefined variable issue
        $pendingCount = Appointment::where('status', 'pending')->count();
        return view('admin.inventory_admin', compact('inventory', 'item', 'pendingCount'));
    }

    // Store a new inventory item
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'expiration_date' => 'nullable|date',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'items_per_unit' => 'required|integer|min:1',
            'supplier' => 'nullable|string|max:255',
            'expiration_type' => 'required|string|in:expirable,inexpirable',
            'category' => 'required|string|max:255',
        ]);
    
        // Check expiration type and adjust expiration_date accordingly
        if ($request->input('expiration_type') === 'inexpirable') {
            $validated['expiration_date'] = null;
        }
    
        // Create and save the new inventory item
        Inventory::create($validated);
    
        return redirect()->route('admin.inventory_admin')->with('success', 'Item added successfully!');
    }
    
    

    // Update an existing inventory item
    public function update(Request $request, $id)
{
    $item = Inventory::find($id);

    if (!$item) {
        return redirect()->back()->with('error', 'Item not found');
    }

    // Validate the input data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'low_stock_threshold' => 'required|integer|min:1',
        'unit' => 'required|string|max:50',
        'items_per_unit' => 'required|integer|min:1',
        'expiration_date' => 'nullable|date',
        'supplier' => 'nullable|string|max:255',
        'category' => 'required|string|max:255',
    ]);

    // Update the item
    $item->name = $validated['name'];
    $item->price = $validated['price'];
    $item->quantity = $validated['quantity'];
    $item->low_stock_threshold = $validated['low_stock_threshold'];
    $item->unit = $validated['unit'];
    $item->items_per_unit = $validated['items_per_unit'];
    $item->expiration_date = $validated['expiration_date'];
    $item->supplier = $validated['supplier'];
    $item->category = $validated['category'];

    $item->save();

    return redirect()->route('admin.inventory_admin')->with('success', 'Item updated successfully');
}

    
    // In your InventoryController.php
public function destroy($id)
{
    $item = Inventory::find($id);
    if ($item) {
        $item->delete();
        return redirect()->route('admin.inventory_admin')->with('success', 'Item deleted successfully');
    }

    return redirect()->route('admin.inventory_admin')->with('error', 'Item not found');
}

}
