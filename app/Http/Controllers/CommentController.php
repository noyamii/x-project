<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPSTORM_META\type;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        return Post::where('post_id', $id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id)
    {
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
            $imageName = time() . $request->file('image')->getClientOriginalName();
            $request->image->move(public_path('images'), $imageName);
            $imagePath = 'image/' . $imageName;
        }

        Auth::user()->post()->create([
                        'text'          => $request->text,
                        'image_path'    => $imagePath,
                        'post_id'       => $id
                    ]);

        return response('comment sent.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
