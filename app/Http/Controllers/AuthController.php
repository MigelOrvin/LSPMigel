<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.index', [
            'mode' => 'login'
        ]);
    }

    public function showRegister()
    {
        return view('auth.index', [
            'mode' => 'register'
        ]);
    }
    
    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->input('email', '')),
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->status !== 'approved') {

            $userId = $user->id;

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $request->session()->put('registration_user_id', $userId);

            return redirect()->route('registration.status');
        }

        $request->session()->forget('registration_user_id');

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('patient.dashboard');
    }




    public function register(Request $request)
    {
        $request->merge([
            'email' => strtolower($request->input('email', '')),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($validated);

        $request->session()->regenerate();

        $request->session()->put('registration_user_id', $user->id);

        return redirect()->route('registration.status');
    }



    public function showStatus(Request $request)
    {
        $userId = $request->session()->get('registration_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (!$user) {
            $request->session()->forget('registration_user_id');

            return redirect()->route('login');
        }

        if ($user->status === 'approved') {

            $request->session()->forget('registration_user_id');

            return redirect()->route('login')->with(
                'success',
                'Akun Anda telah disetujui dan berhasil terdaftar. Silakan masuk.'
            );
        }

        return response()
            ->view('auth.status', compact('user'))
            ->header('Cache-Control', 'no-store');
    }


}
