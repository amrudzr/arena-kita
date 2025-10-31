<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    /**
     * Display a listing of the fields.
     */
    public function index()
    {
        $fields = Field::with('venue')->get();
        return response()->json([
            'status' => 'success',
            'data' => $fields
        ]);
    }

    /**
     * Store a newly created field in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'field_name' => 'required|string|max:100',
            'sport_type' => 'required|string|max:50',
            'field_photo_url' => 'nullable|image|max:2048', // 2MB Max
            'status' => 'required|string|in:AVAILABLE,UNAVAILABLE,MAINTENANCE'
        ]);

        if ($request->hasFile('field_photo_url')) {
            $path = $request->file('field_photo_url')->store('public/fields');
            $validated['field_photo_url'] = Storage::url($path);
        }

        $field = Field::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Field created successfully',
            'data' => $field
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified field.
     */
    public function show(Field $field)
    {
        return response()->json([
            'status' => 'success',
            'data' => $field->load('venue')
        ]);
    }

    /**
     * Update the specified field in storage.
     */
    public function update(Request $request, Field $field)
    {
        $validated = $request->validate([
            'venue_id' => 'sometimes|required|exists:venues,id',
            'field_name' => 'sometimes|required|string|max:100',
            'sport_type' => 'sometimes|required|string|max:50',
            'field_photo_url' => 'nullable|image|max:2048',
            'status' => 'sometimes|required|string|in:AVAILABLE,UNAVAILABLE,MAINTENANCE'
        ]);

        if ($request->hasFile('field_photo_url')) {
            // Delete old image if exists
            if ($field->field_photo_url) {
                $oldPath = str_replace('/storage/', 'public/', $field->field_photo_url);
                Storage::delete($oldPath);
            }

            $path = $request->file('field_photo_url')->store('public/fields');
            $validated['field_photo_url'] = Storage::url($path);
        }

        $field->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Field updated successfully',
            'data' => $field
        ]);
    }

    /**
     * Remove the specified field from storage.
     */
    public function destroy(Field $field)
    {
        if ($field->field_photo_url) {
            $path = str_replace('/storage/', 'public/', $field->field_photo_url);
            Storage::delete($path);
        }

        $field->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Field deleted successfully'
        ]);
    }
}
