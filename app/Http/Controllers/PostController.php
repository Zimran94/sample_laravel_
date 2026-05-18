<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET all posts
    public function index()
    {
        return response()->json(Post::all());
    }

    // POST create new post
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $post = Post::create($validated);

        return response()->json([
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    // GET single post
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json($post);
    }

    // PUT update post
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        $post->update($request->all());

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post
        ]);
    }

    // DELETE post
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}