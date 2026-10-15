<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;

class PortfolioApiController extends Controller
{
    public function projects() { return Project::with('technologies')->where('status', 'published')->orderByDesc('featured')->orderBy('display_order')->paginate(12); }
    public function project(Project $project) { abort_unless($project->status === 'published', 404); return $project->load('technologies', 'images'); }
    public function skills() { return Skill::where('status', 'active')->orderBy('display_order')->get(); }
    public function services() { return Service::where('status', 'active')->orderBy('display_order')->get(); }
    public function articles() { return Article::where('status', 'published')->whereNotNull('published_at')->latest('published_at')->paginate(12); }
}
