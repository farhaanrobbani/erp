<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CertificateLegality;
use App\Models\Post;
use App\Models\Project;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::where('is_published', true)
            ->where('is_featured', true)
            ->latest('start_date')
            ->limit(3)
            ->get();

        $latestPosts = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(3)
            ->get();

        $activeCertificates = CertificateLegality::where('is_active', true)
            ->orderBy('type')
            ->get();

        return view('home', compact('featuredProjects', 'latestPosts', 'activeCertificates'));
    }
}
