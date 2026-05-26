<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    // GET ALL POSTS WITH CACHE
    public function index()
    {
        $posts = Cache::remember('homepage_posts', 60, function () {
            return Post::latest()->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'Posts fetched successfully',
            'data' => $posts
        ]);
    }

    // STORE NEW POST
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        // CLEAR CACHE
        Cache::forget('homepage_posts');

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ]);
    }

    // SHOW SINGLE POST
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $post
        ]);
    }

    // UPDATE POST
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        // CLEAR CACHE
        Cache::forget('homepage_posts');

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ]);
    }

    // DELETE POST
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found'
            ], 404);
        }

        $post->delete();

        // CLEAR CACHE
        Cache::forget('homepage_posts');

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully'
        ]);
    }
}