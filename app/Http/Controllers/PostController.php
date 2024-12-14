<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index () {
        return Post::inRandomOrder()->limit(10)->get();
    }

    public function store (Request $request) {
        $request->validate([
            'image' => 'nullable|mimes:jpg,png,jpeg|max:10240',
            'text' => 'nullable',
            'tags' => 'nullable',
        ]);

        if (!($request->image or $request->text)){
            return response('image or text required', 400);
        }

        $imagePath = null;
        if ($request->image){
            $imageName = time() . $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'image/' . $imageName;
        }

        $post = Auth::user()->post()->create([
                        'text' => $request->text,
                        'image_path' => $imagePath,
                    ]);


        if ($request['tags'] ?? false) {
            foreach (explode(',', $request['tags']) as $tag) {
                $post->tag($tag);
            }
        }

        return response('post created');

    }
    public function show (int $id) {
        
    }
}
