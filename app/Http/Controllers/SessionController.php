<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class SessionController extends Controller
{
    public function store()
    {
        $attributes = request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (! Auth::attempt($attributes)) {
            throw ValidationValidationException::withMessages([
                'username' => 'Sorry, those credentials do not match.',
            ]);
        }

        request()->session()->regenerate();

        return redirect('/');
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/');
    }
}
