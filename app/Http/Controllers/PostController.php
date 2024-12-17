<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index () {
        $posts = Post::inRandomOrder()->limit(10)->with('user', 'tags')->get();
        $response = [];
        foreach ($posts as $post){ 
            $array['id'] = $post->id;
            $array['text'] = $post->text;
            if ($post->tags) {
                foreach ($post->tags as $tag) {
                    $array['tags'][] = $tag->name;
                }
            }
            $array['replied_to'] = $post->post_id;
            $array['image_path'] = $post->image_path;
            $array['user_id'] = $post->user_id;
            $array['user_name'] = $post->user->name;
            $response[] = $array;
        }
        
        return $response;

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
    public function destroy (int $id) {
        // user role might be null
        // user not allowed 
        // couldn't find the desired user
        if ((Auth::user()->role == 'admin') or (Auth::user()->id == Post::find($id)->user_id)){
            Post::destroy($id);
        }
    }
}
