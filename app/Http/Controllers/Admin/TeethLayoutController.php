<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tooth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeethLayoutController extends Controller
{
    public function index()
    {
        $users = User::all(); // Fetch all users
        return view('admin.teeth_layout', compact('users'));
    }

public function getTeethLayout($userId)
{
    $teeth = Tooth::where('user_id', $userId)->get();

    return response()->json(['teeth' => $teeth]);
}

    public function addTooth(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'number' => 'required|integer|min:1|max:32', // Ensure valid tooth number
        ]);

        $tooth = Tooth::create([
            'user_id' => $request->user_id,
            'x' => $request->x,
            'y' => $request->y,
            'number' => $request->number,
        ]);

        return response()->json(['message' => 'New tooth added successfully.', 'tooth' => $tooth]);
    }

public function removeTooth($toothId)
{
    $tooth = Tooth::findOrFail($toothId);
    $tooth->delete(); // Permanently delete the tooth from the database

    return response()->json(['message' => 'Tooth deleted successfully.']);
}


public function getUserTeethLayout()
{
    $userId = auth()->id(); // Get the logged-in user's ID
    $teeth = Tooth::where('user_id', $userId)->get();

    return response()->json(['teeth' => $teeth]);
}

    public function initializeTeethLayout($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Prevent duplicate initialization
            if (Tooth::where('user_id', $userId)->exists()) {
                return response()->json(['message' => 'Teeth layout already exists for this user.'], 400);
            }

            $teeth = [];
            for ($i = 1; $i <= 32; $i++) {
                $teeth[] = [
                    'user_id' => $userId,
                    'number' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Tooth::insert($teeth);

            return response()->json(['message' => 'Default teeth layout initialized successfully.']);
        } catch (\Exception $e) {
            Log::error('Teeth layout initialization failed: ' . $e->getMessage());
            return response()->json(['message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }


    public function saveTeethLayout(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        foreach ($request->teeth as $toothData) {
            if (isset($toothData['id'])) {
                // Update existing tooth
                $tooth = Tooth::findOrFail($toothData['id']);
                $tooth->update(['number' => $toothData['number']]);
            } else {
                // Add new tooth
                Tooth::create([
                    'user_id' => $userId,
                    'number' => $toothData['number']
                ]);
            }
        }

        return response()->json(['message' => 'Teeth layout saved successfully.']);
    }

public function updateToothPosition(Request $request, $toothId)
{
    $request->validate([
        'x' => 'required|numeric',
        'y' => 'required|numeric',
    ]);

    $tooth = Tooth::findOrFail($toothId);
    $tooth->update([
        'x' => $request->x,
        'y' => $request->y,
    ]);

    return response()->json(['message' => 'Tooth position updated successfully.', 'tooth' => $tooth]);
}
}
