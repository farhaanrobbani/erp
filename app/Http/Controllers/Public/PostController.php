<?php

namespace App\Http\Controllers\Public;

use App\Enums\PostCategory;
use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->when(request('category'), fn ($query, $category) => $query->where('category', $category))
            ->latest('published_at')
            ->paginate(9);

        $categories = PostCategory::cases();

        return view('posts.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $related = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('posts.show', compact('post', 'related'));
    }
}
