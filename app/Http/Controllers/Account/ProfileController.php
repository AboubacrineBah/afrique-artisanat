<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email', 'phone']));

        if ($request->filled('password')) {
            $user->password = $request->input('password'); // haché par le cast du modèle
        }

        $user->save();

        return redirect()->route('account.profile.edit')->with('success', 'Votre profil a été mis à jour.');
    }
}
