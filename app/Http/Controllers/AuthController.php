<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if input is email or phone
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        if (Auth::attempt([$field => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Account is deactivated.']);
            }
            
            // Redirect based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
                case 'driver':
                    return redirect()->route('driver.dashboard')->with('success', 'Welcome back, Driver!');
                default:
                    return redirect()->route('client.dashboard')->with('success', 'Welcome back!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'client'
        ]);

        // Generate referral code
        $user->referral_code = strtoupper(substr(md5(uniqid()), 0, 8));
        $user->save();

        // Create wallet
        Wallet::create(['user_id' => $user->id]);

        // Handle referral
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            
            if ($referrer) {
                Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                    'referral_code' => $request->referral_code,
                    'status' => 'pending'
                ]);

                // Give K20 to new user
                if ($user->wallet) {
                    $user->wallet->credit(20, "Welcome bonus from referral");
                }
            }
        }

        Auth::login($user);

        return redirect()->route('client.dashboard')->with('success', 'Registration successful! Welcome to Laundry Logistics.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}