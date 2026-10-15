<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Design;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private function settings(): array
    {
        return Setting::pluck('value', 'key')->all();
    }

    public function index()
    {
        return view('home', [
            'settings' => $this->settings(),
            'projects' => Project::with('technologies')->where('status', 'published')->orderByDesc('featured')->orderBy('display_order')->take(3)->get(),
            'skills' => Skill::where('status', 'active')->orderBy('display_order')->get(),
            'services' => Service::where('status', 'active')->orderBy('display_order')->get(),
            'designs' => Design::where('status', 'published')->orderByDesc('featured')->orderBy('display_order')->take(6)->get(),
            'articles' => Article::where('status', 'published')->whereNotNull('published_at')->latest('published_at')->take(3)->get(),
        ]);
    }

    public function about() { return view('page', ['settings' => $this->settings(), 'page' => 'about']); }
    public function web3() { return view('page', ['settings' => $this->settings(), 'page' => 'web3']); }

    public function skills()
    {
        return view('skills', ['settings' => $this->settings(), 'skills' => Skill::where('status', 'active')->orderBy('category')->orderBy('display_order')->get()->groupBy('category')]);
    }

    public function services()
    {
        return view('services', ['settings' => $this->settings(), 'services' => Service::where('status', 'active')->orderBy('display_order')->get()]);
    }

    public function projects(Request $request)
    {
        $projects = Project::with('technologies')->where('status', 'published')
            ->when($request->q, fn ($query, $term) => $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('short_description', 'like', "%{$term}%")->orWhere('category', 'like', "%{$term}%")->orWhereHas('technologies', fn ($t) => $t->where('name', 'like', "%{$term}%"))))
            ->when($request->category && $request->category !== 'All', fn ($query, $category) => $query->where('category', $category))
            ->orderByDesc('featured')->orderBy('display_order')->paginate(9)->withQueryString();
        return view('projects.index', compact('projects') + ['settings' => $this->settings()]);
    }

    public function project(Project $project)
    {
        abort_unless($project->status === 'published', 404);
        return view('projects.show', ['settings' => $this->settings(), 'project' => $project->load('technologies', 'images')]);
    }

    public function designs(Request $request)
    {
        $designs = Design::where('status', 'published')->when($request->category, fn ($q, $category) => $q->where('category', $category))->orderByDesc('featured')->orderBy('display_order')->get();
        return view('designs', ['settings' => $this->settings(), 'designs' => $designs]);
    }

    public function articles(Request $request)
    {
        $articles = Article::where('status', 'published')->whereNotNull('published_at')
            ->when($request->q, fn ($q, $term) => $q->where(fn ($search) => $search->where('title', 'like', "%{$term}%")->orWhere('excerpt', 'like', "%{$term}%")->orWhere('content', 'like', "%{$term}%")))
            ->when($request->category, fn ($q, $category) => $q->where('category', $category))->latest('published_at')->paginate(9)->withQueryString();
        return view('articles.index', compact('articles') + ['settings' => $this->settings()]);
    }

    public function article(Article $article)
    {
        abort_unless($article->status === 'published' && $article->published_at, 404);
        return view('articles.show', ['settings' => $this->settings(), 'article' => $article]);
    }
}
