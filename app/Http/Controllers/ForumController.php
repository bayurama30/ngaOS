<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        return view('forum.index');
    }

    public function show(Post $post)
    {
        return view('forum.show', [
            'post' => $post,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'category' => 'required|in:umum,tafsir,hadis,doa,fiqih',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($validated);

        return redirect()->route('forum.index')->with('success', 'Postingan berhasil dibuat!');
    }

    public function comment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        $post->increment('comments_count');

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function like(Request $request, Post $post)
    {
        $user = $request->user();

        $existingLike = $post->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $post->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ]);
    }

    public function bookmark(Request $request, Post $post)
    {
        $user = $request->user();

        $existing = $user->bookmarks()
            ->where('bookmarkable_type', Post::class)
            ->where('bookmarkable_id', $post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            $user->bookmarks()->create([
                'bookmarkable_type' => Post::class,
                'bookmarkable_id' => $post->id,
            ]);
            $bookmarked = true;
        }

        return response()->json(['bookmarked' => $bookmarked]);
    }
}
