<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', ['stats' => [
            ['label' => 'Total projects', 'value' => Project::count()], ['label' => 'Published projects', 'value' => Project::where('status', 'published')->count()],
            ['label' => 'Active skills', 'value' => Skill::where('status', 'active')->count()], ['label' => 'Published articles', 'value' => Article::where('status', 'published')->count()],
            ['label' => 'Unread messages', 'value' => Message::where('status', 'unread')->count()],
        ], 'messages' => Message::latest()->take(5)->get()]);
    }
}
