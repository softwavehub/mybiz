<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Seller;
use App\Models\Merchant;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show Login Form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle Login Submission
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectUserByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show Registration Form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Handle Account Registration (Seller or Merchant)
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:merchant,seller'],
            'business_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'merchant') {
            $starterPlan = SubscriptionPlan::firstOrCreate(
                ['name' => 'Starter Free Plan'],
                ['price' => 0.00, 'product_limit' => 25, 'billing_cycle' => 'monthly']
            );

            $subdomain = Str::slug($data['business_name']);
            if (Merchant::where('subdomain', $subdomain)->exists()) {
                $subdomain .= '-' . rand(100, 999);
            }

            Merchant::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $starterPlan->id,
                'store_name' => $data['business_name'],
                'subdomain' => $subdomain,
                'kyc_status' => 'pending',
                'health_score' => 100,
                'health_tier' => 'excellent',
                'escrow_tier' => '15_days',
                'store_status' => 'live',
            ]);
        } elseif ($data['role'] === 'seller') {
            Seller::create([
                'user_id' => $user->id,
                'company_name' => $data['business_name'],
                'kyc_status' => 'pending',
                'health_score' => 100,
                'health_tier' => 'excellent',
                'escrow_tier' => '15_days',
            ]);
        }

        Auth::login($user);

        return $this->redirectUserByRole($user);
    }

    /**
     * Handle Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    /**
     * Helper to redirect authenticated user to their role-specific dashboard
     */
    private function redirectUserByRole(User $user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'seller':
                return redirect()->route('seller.dashboard');
            case 'merchant':
            default:
                return redirect()->route('merchant.dashboard');
        }
    }
}
