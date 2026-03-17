<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->locations;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_phone' => 'required|string',
            'instructions' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        if ($request->has('is_default') && $request->is_default) {
            // Reset other default locations
            $request->user()->locations()->update(['is_default' => false]);
        }

        $location = Location::create($data);

        return response()->json($location, 201);
    }

    public function update(Request $request, Location $location)
    {
        if ($location->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'address' => 'string',
            'contact_phone' => 'string',
            'instructions' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('is_default') && $request->is_default) {
            // Reset other default locations
            $request->user()->locations()
                ->where('id', '!=', $location->id)
                ->update(['is_default' => false]);
        }

        $location->update($request->all());

        return response()->json($location);
    }

    public function destroy(Request $request, Location $location)
    {
        if ($location->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $location->delete();

        return response()->json(['message' => 'Location deleted successfully']);
    }

    public function setDefault(Request $request, Location $location)
    {
        if ($location->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Reset all locations to non-default
        $request->user()->locations()->update(['is_default' => false]);
        
        // Set this location as default
        $location->update(['is_default' => true]);

        return response()->json(['message' => 'Default location set successfully']);
    }
}