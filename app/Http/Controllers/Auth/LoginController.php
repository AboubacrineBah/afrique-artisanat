<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        if (! Auth::attempt($request->safe()->only(['email', 'password']), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $fallback = $request->user()->isAdmin() ? route('admin.dashboard') : route('home');

        return redirect()->intended($fallback);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Vous êtes déconnecté.');
    }
}
