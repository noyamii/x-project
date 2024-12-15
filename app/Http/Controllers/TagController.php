<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index () {
        Tag::all('name');
    }
    public function destroy (string $name) {
        // user role might be null
        // user not allowed 
        // couldn't find the desired tag
        if (Auth::user()->role == 'admin') {
            Tag::where('name', strtolower($name))->delete();
        }
    }
}
