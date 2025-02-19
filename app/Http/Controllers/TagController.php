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
        if (Auth::user()->role == 'admin') {
            Tag::where('name', strtolower($name))->delete();
        }
    }
}
