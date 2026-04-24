<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Location;

class ClientController extends Controller
{
    /**
     * Update student details for the authenticated user
     */
    public function updateStudentDetails(Request $request)
    {
        try {
            // Validate the request with security rules
            $validator = Validator::make($request->all(), [
                'hostel_name' => 'nullable|string|max:255',
                'room_number' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'student_id' => 'nullable|string|max:50',
                'alternative_phone' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                        'message' => 'Validation failed'
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            $user = Auth::user();
            
            // REMOVED THE RATE LIMITING CODE - No more waiting
            
            // Update user fields
            $user->hostel_name = $request->hostel_name;
            $user->room_number = $request->room_number;
            $user->phone = $request->phone;
            $user->student_id = $request->student_id;
            $user->alternative_phone = $request->alternative_phone;
            $user->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student details updated successfully!',
                    'user' => [
                        'hostel_name' => $user->hostel_name,
                        'room_number' => $user->room_number,
                        'phone' => $user->phone,
                        'student_id' => $user->student_id,
                        'alternative_phone' => $user->alternative_phone,
                        'email' => $user->email,
                        'name' => $user->name
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Student details updated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Student details update failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating details. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while updating details. Please try again.');
        }
    }
    
    /**
     * Get current user's student details
     */
    public function getStudentDetails(Request $request)
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'hostel_name' => $user->hostel_name,
                    'room_number' => $user->room_number,
                    'phone' => $user->phone,
                    'student_id' => $user->student_id,
                    'alternative_phone' => $user->alternative_phone,
                    'email' => $user->email,
                    'name' => $user->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student details'
            ], 500);
        }
    }
    
    /**
     * Detect location from student details
     */
    public function detectLocation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'hostel_name' => 'required|string|max:255',
                'room_number' => 'nullable|string|max:255',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $hostelName = strtolower($request->hostel_name);
            
            $campusLocations = [
                'nipa' => ['lat' => -15.4100, 'lng' => 28.3150, 'name' => 'NIPA', 'address' => 'Great East Road, Lusaka'],
                'unza' => ['lat' => -15.3925, 'lng' => 28.3328, 'name' => 'UNZA', 'address' => 'Great East Road, Lusaka'],
                'university of zambia' => ['lat' => -15.3925, 'lng' => 28.3328, 'name' => 'UNZA', 'address' => 'Great East Road, Lusaka'],
                'cbu' => ['lat' => -12.7969, 'lng' => 28.2325, 'name' => 'CBU', 'address' => 'Jambo Drive, Kitwe'],
                'copperbelt university' => ['lat' => -12.7969, 'lng' => 28.2325, 'name' => 'CBU', 'address' => 'Jambo Drive, Kitwe'],
                'evelyn hone' => ['lat' => -15.4200, 'lng' => 28.3000, 'name' => 'Evelyn Hone College', 'address' => 'Independence Avenue, Lusaka'],
            ];
            
            $location = null;
            foreach ($campusLocations as $key => $coords) {
                if (strpos($hostelName, $key) !== false) {
                    $location = $coords;
                    break;
                }
            }
            
            if ($location) {
                $user = Auth::user();
                if ($user) {
                    $user->current_latitude = $location['lat'];
                    $user->current_longitude = $location['lng'];
                    $user->location_updated_at = now();
                    $user->save();
                }
                
                return response()->json([
                    'success' => true,
                    'location' => $location,
                    'message' => 'Location detected successfully',
                    'address' => $location['name'] . ($request->room_number ? ', Room ' . $request->room_number : '')
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Could not detect location for "' . $request->hostel_name . '".'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Location detection failed. Please try again.'
            ], 500);
        }
    }
}