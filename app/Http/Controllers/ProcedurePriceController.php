<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;

class ProcedurePriceController extends Controller
{
    // Show the form with existing prices
    public function index()
    {
        $procedures = ProcedurePrice::paginate(20);  // Paginate with 20 items per page
        $allProcedures = ProcedurePrice::all(); // For statistics
        $inventories = \App\Models\Inventory::all(); // Get all inventory items for the modal
        return view('admin.procedure_prices', compact('procedures', 'allProcedures', 'inventories'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'procedure_name' => 'required|string|max:255',  // Procedure name must be a string and not too long
            'price' => 'required|numeric|min:0',  // Price must be a number and not negative
            'duration' => 'required|string',  // Duration must be a string
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
            'description' => 'nullable|string|max:1000', // Validate description
        ]);

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('procedure_images', 'public'); // Store image in public storage
        }

        // Create a new ProcedurePrice record
        ProcedurePrice::create([
            'procedure_name' => $validated['procedure_name'],
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'image_path' => $imagePath, // Save the relative path
            'description' => $validated['description'],
        ]);

        // Redirect back with success message
        return redirect()->route('admin.procedure_prices')->with('success', 'New procedure price added successfully.');
    }

    // Handle the update request
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'price' => 'required|numeric|min:0', // Price must be a number and not negative
            'duration' => 'required|string',    // Duration must be a string
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
            'description' => 'nullable|string|max:1000', // Validate description
        ]);

        // Find the procedure by ID and update the price, duration, image, and description
        $procedure = ProcedurePrice::findOrFail($id);

        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('procedure_images', 'public'); // Store image in public storage
            $procedure->image_path = $imagePath; // Save the relative path
        }

        $procedure->price = $validated['price'];
        $procedure->duration = $validated['duration'];
        $procedure->description = $validated['description'];
        $procedure->save(); // Save the updated record

        // Redirect back with a success message
        return redirect()->route('admin.procedure_prices')->with('success', 'Procedure price updated successfully.');
    }

    public function destroy($id)
    {
        $procedure = ProcedurePrice::findOrFail($id);
        $procedure->delete();

        return redirect()->route('admin.procedure_prices')->with('success', 'Procedure price deleted successfully.');
    }

    public function getProcedureDetails(Request $request)
    {
        $procedure = \App\Models\ProcedurePrice::where('procedure_name', $request->procedure)->first();

        if ($procedure) {
            return response()->json([
                'price' => $procedure->price,
                'duration' => $procedure->duration, // Ensure this field exists in DB
                'image_path' => $procedure->image_path,
                'description' => $procedure->description,
            ]);
        }

        return response()->json(['error' => 'Procedure not found'], 404);
    }

    /**
     * Get supplies for a specific procedure
     */
    public function getProcedureSupplies($procedureId)
    {
        $procedure = ProcedurePrice::with('procedureInventories.inventory')->findOrFail($procedureId);
        
        $supplies = $procedure->procedureInventories->map(function($pi) {
            return [
                'inventory_id' => $pi->inventory_id,
                'quantity_used' => $pi->quantity_used,
                'inventory_name' => $pi->inventory->name,
                'unit' => $pi->inventory->unit,
            ];
        });

        return response()->json([
            'success' => true,
            'supplies' => $supplies
        ]);
    }

    /**
     * Save/Update supplies for a procedure
     */
    public function saveProcedureSupplies(Request $request, $procedureId)
    {
        $request->validate([
            'inventory_id' => 'required|array',
            'inventory_id.*' => 'required|exists:inventories,id',
            'quantity_used' => 'required|array',
            'quantity_used.*' => 'required|numeric|min:0.01',
        ]);

        $procedure = ProcedurePrice::findOrFail($procedureId);

        // Delete existing supplies for this procedure
        $procedure->procedureInventories()->delete();

        // Add new supplies
        $inventoryIds = $request->input('inventory_id');
        $quantities = $request->input('quantity_used');

        foreach ($inventoryIds as $index => $inventoryId) {
            if (!empty($inventoryId) && !empty($quantities[$index])) {
                \App\Models\ProcedureInventory::create([
                    'procedure_price_id' => $procedureId,
                    'inventory_id' => $inventoryId,
                    'quantity_used' => $quantities[$index],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Supplies updated successfully'
        ]);
    }
}
