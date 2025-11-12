<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ToothRecord;
use App\Models\ToothNote;
use App\Models\ToothImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ToothRecordController extends Controller
{
    /**
     * Display the teeth layout management page
     */
    public function index()
    {
        $users = User::where('usertype', 'user')->orderBy('name')->get();
        return view('admin.teeth_layout', compact('users'));
    }

    /**
     * Get all tooth records for a specific user
     */
    public function getRecords($userId)
    {
        try {
            $records = ToothRecord::where('user_id', $userId)
                ->orderBy('tooth_number')
                ->get();

            return response()->json([
                'success' => true,
                'records' => $records
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching tooth records: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching records',
                'records' => []
            ], 500);
        }
    }

    /**
     * Initialize default 32-tooth layout for a user
     */
    public function initializeLayout($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Check if already initialized
            $existingCount = ToothRecord::where('user_id', $userId)->count();
            if ($existingCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teeth layout already exists for this patient. You can modify individual teeth instead.'
                ], 400);
            }

            // Create 32 tooth records
            $teeth = [];
            for ($i = 1; $i <= 32; $i++) {
                $quadrant = $this->getQuadrant($i);
                $toothType = $this->getToothType($i);
                
                $teeth[] = [
                    'user_id' => $userId,
                    'tooth_number' => $i,
                    'quadrant' => $quadrant,
                    'tooth_type' => $toothType,
                    'condition' => 'healthy',
                    'color_code' => '#10b981', // Green for healthy
                    'is_missing' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ToothRecord::insert($teeth);

            // Log activity
            Log::info("Teeth layout initialized for user ID: {$userId} by admin ID: " . Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Default 32-tooth layout initialized successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Teeth layout initialization failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update tooth record with condition, notes, etc.
     */
    public function updateRecord(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'tooth_number' => 'required|integer|min:1|max:32',
                'condition' => 'required|string',
                'quadrant' => 'required|string',
                'tooth_type' => 'required|string',
                'color_code' => 'required|string',
                'is_missing' => 'required|boolean',
                'note_content' => 'nullable|string',
                'note_type' => 'nullable|string'
            ]);

            // Find or create tooth record
            $toothRecord = ToothRecord::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'tooth_number' => $validated['tooth_number']
                ],
                [
                    'condition' => $validated['condition'],
                    'quadrant' => $validated['quadrant'],
                    'tooth_type' => $validated['tooth_type'],
                    'color_code' => $validated['color_code'],
                    'is_missing' => $validated['is_missing'],
                    'last_treatment_date' => now()
                ]
            );

            // Add note if provided
            if (!empty($validated['note_content'])) {
                ToothNote::create([
                    'tooth_record_id' => $toothRecord->id,
                    'created_by' => Auth::id(),
                    'note_type' => $validated['note_type'] ?? 'observation',
                    'content' => $validated['note_content'],
                    'note_date' => now()
                ]);
            }

            // Log activity
            Log::info("Tooth #{$validated['tooth_number']} updated for user ID: {$validated['user_id']} by admin ID: " . Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Tooth record updated successfully!',
                'record' => $toothRecord
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating tooth record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notes for a specific tooth record
     */
    public function getNotes($toothRecordId)
    {
        try {
            $notes = ToothNote::where('tooth_record_id', $toothRecordId)
                ->with('creator:id,name')
                ->orderBy('note_date', 'desc')
                ->get()
                ->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'note_type' => $note->note_type,
                        'content' => $note->content,
                        'note_date' => Carbon::parse($note->note_date)->format('M d, Y'),
                        'creator' => $note->creator->name ?? 'Unknown'
                    ];
                });

            return response()->json([
                'success' => true,
                'notes' => $notes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching tooth notes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'notes' => []
            ], 500);
        }
    }

    /**
     * Add a note to a tooth record
     */
    public function addNote(Request $request, $toothRecordId)
    {
        try {
            $validated = $request->validate([
                'note_type' => 'required|string',
                'content' => 'required|string',
            ]);

            $note = ToothNote::create([
                'tooth_record_id' => $toothRecordId,
                'created_by' => Auth::id(),
                'note_type' => $validated['note_type'],
                'content' => $validated['content'],
                'note_date' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully!',
                'note' => $note
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding tooth note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding note'
            ], 500);
        }
    }

    /**
     * Get statistics for a user's dental health
     */
    public function getStatistics($userId)
    {
        try {
            $records = ToothRecord::where('user_id', $userId)->get();

            $stats = [
                'total' => $records->where('is_missing', false)->count(),
                'healthy' => $records->where('condition', 'healthy')->count(),
                'watch' => $records->where('condition', 'watch')->count(),
                'cavity' => $records->where('condition', 'cavity')->count(),
                'treatment_needed' => $records->where('condition', 'treatment_needed')->count(),
                'crown' => $records->where('condition', 'crown')->count(),
                'implant' => $records->where('condition', 'implant')->count(),
                'root_canal' => $records->where('condition', 'root_canal')->count(),
                'missing' => $records->where('is_missing', true)->count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'stats' => []
            ], 500);
        }
    }

    /**
     * Bulk update positions (for drag & drop)
     */
    public function updatePositions(Request $request)
    {
        try {
            $validated = $request->validate([
                'positions' => 'required|array',
                'positions.*.tooth_record_id' => 'required|exists:tooth_records,id',
                'positions.*.x_position' => 'required|numeric',
                'positions.*.y_position' => 'required|numeric',
            ]);

            foreach ($validated['positions'] as $position) {
                ToothRecord::where('id', $position['tooth_record_id'])
                    ->update([
                        'x_position' => $position['x_position'],
                        'y_position' => $position['y_position']
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Positions updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating positions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating positions'
            ], 500);
        }
    }

    /**
     * Helper: Get quadrant based on tooth number
     */
    private function getQuadrant($number)
    {
        if ($number <= 8) return 'upper_right';
        if ($number <= 16) return 'upper_left';
        if ($number <= 24) return 'lower_left';
        return 'lower_right';
    }

    /**
     * Helper: Get tooth type based on position
     */
    private function getToothType($number)
    {
        $mod = $number % 8;
        if ($mod === 0) $mod = 8;
        
        if ($mod <= 2) return 'incisor';
        if ($mod === 3) return 'canine';
        if ($mod <= 5) return 'premolar';
        return 'molar';
    }

    /**
     * Delete/Remove a tooth record (mark as missing)
     */
    public function markAsMissing($toothRecordId)
    {
        try {
            $toothRecord = ToothRecord::findOrFail($toothRecordId);
            $toothRecord->update([
                'is_missing' => true,
                'condition' => 'missing',
                'color_code' => '#6b7280'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tooth marked as missing successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking tooth as missing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating tooth status'
            ], 500);
        }
    }

    /**
     * Upload tooth image/x-ray
     */
    public function uploadImage(Request $request, $toothRecordId)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:5120', // 5MB max
                'image_type' => 'required|string',
                'description' => 'nullable|string'
            ]);

            $toothRecord = ToothRecord::findOrFail($toothRecordId);

            // Store image
            $path = $request->file('image')->store('tooth-images', 'public');

            $image = ToothImage::create([
                'tooth_record_id' => $toothRecordId,
                'image_type' => $request->image_type,
                'file_path' => $path,
                'file_name' => $request->file('image')->getClientOriginalName(),
                'description' => $request->description,
                'image_date' => now(),
                'uploaded_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully!',
                'image' => $image
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading tooth image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image'
            ], 500);
        }
    }

    /**
     * Get all images for a tooth record
     */
    public function getImages($toothRecordId)
    {
        try {
            $images = ToothImage::where('tooth_record_id', $toothRecordId)
                ->with('uploader:id,name')
                ->orderBy('image_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'images' => $images
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching tooth images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'images' => []
            ], 500);
        }
    }
}
