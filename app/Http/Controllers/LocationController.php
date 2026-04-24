<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        if ($request->has('is_default') && $request->is_default) {
            // Reset other default locations
            $request->user()->locations()->update(['is_default' => false]);
        }

        $location = Location::create($data);

        if ($request->ajax()) {
            return response()->json($location, 201);
        }
        
        return redirect()->back()->with('success', 'Location added successfully');
    }

    public function update(Request $request, Location $location)
    {
        if ($location->user_id !== $request->user()->id) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403);
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
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        if ($request->has('is_default') && $request->is_default) {
            // Reset other default locations
            $request->user()->locations()
                ->where('id', '!=', $location->id)
                ->update(['is_default' => false]);
        }

        $location->update($request->all());

        if ($request->ajax()) {
            return response()->json($location);
        }
        
        return redirect()->back()->with('success', 'Location updated successfully');
    }
 
    public function destroy(Request $request, Location $location)
    {
        // Check if user is authorized
        if (!Auth::check() || $location->user_id !== Auth::id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }
        
        try {
            // Check if location is used as pickup location in any order
            $usedAsPickup = Order::where('pickup_location_id', $location->id)->exists();
            
            // Check if location is used as delivery location in any order
            $usedAsDelivery = Order::where('delivery_location_id', $location->id)->exists();
            
            if ($usedAsPickup || $usedAsDelivery) {
                $message = 'Cannot delete location because it is used in existing orders.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return back()->with('error', $message);
            }
            
            // Check if this is the default location
            $wasDefault = $location->is_default;
            
            // Delete the location
            $location->delete();
            
            // If this was the default location and user has other locations, set another as default
            if ($wasDefault) {
                $newDefault = Auth::user()->locations()->first();
                if ($newDefault) {
                    $newDefault->update(['is_default' => true]);
                }
            }
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Location deleted successfully',
                    'was_default' => $wasDefault
                ]);
            }
            
            return redirect()->back()->with('success', 'Location deleted successfully');
            
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Failed to delete location: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete location: ' . $e->getMessage());
        }
    }

    public function setDefault(Request $request, Location $location)
    {
        if ($location->user_id !== $request->user()->id) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        // Reset all locations to non-default
        $request->user()->locations()->update(['is_default' => false]);
        
        // Set this location as default
        $location->update(['is_default' => true]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Default location set successfully']);
        }
        
        return redirect()->back()->with('success', 'Default location set successfully');
    }
    
    /**
     * Get all locations for the authenticated user (for API calls)
     */
    public function getUserLocations(Request $request)
    {
        $locations = Auth::user()->locations()->orderBy('is_default', 'desc')->get();
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'locations' => $locations]);
        }
        
        return $locations;
    }
}