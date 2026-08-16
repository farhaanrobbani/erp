<?php

namespace App\Http\Controllers\Public;

use App\Enums\ProjectCategory;
use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_published', true)
            ->when(request('category'), fn ($query, $category) => $query->where('category', $category))
            ->latest('start_date')
            ->paginate(9);

        $categories = ProjectCategory::cases();

        return view('projects.index', compact('projects', 'categories'));
    }

    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
            ->where('is_published', true)
            ->with('galleries')
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }
}
