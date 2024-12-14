<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function store (Request $request) {
        $request->validate([
            'image' => 'nullable|mimes:jpg,png,jpeg|max:10240',
            'text' => 'nullable',
        ]);

        // if both is empty
        if (($request->image or $request->text) == null){
            return response('image or text required', 400);
        }

        $imagePath = null;
        if ($request->image){
            $imagePath = time() . $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images'), $imagePath);
            $imagePath = 'image/' . $imagePath;
        }

        Auth::user()->post()->create([
                        'text' => $request->text,
                        'image_path' => $imagePath,
                    ]);

        return response('post created');

    }
}
