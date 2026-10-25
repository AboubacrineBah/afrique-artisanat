<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\NewVisitorRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        // Le mot de passe est haché automatiquement (cast "hashed" du modèle User) ; le rôle reste "customer" par défaut
        $user = User::create($request->safe()->only(['name', 'email', 'phone', 'password']));

        Auth::login($user);
        $request->session()->regenerate();

        try {
            Mail::to(config('shop.admin_email'))->send(new NewVisitorRegistered($user));
        } catch (Throwable $e) {
            report($e);
        }

        return redirect()->intended(route('home'))->with('success', 'Bienvenue sur Afrique Artisanat, '.$user->name.' !');
    }
}
