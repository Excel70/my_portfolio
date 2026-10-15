<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Design;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    private function definition(string $resource): array
    {
        return match ($resource) {
            'projects' => ['title' => 'Projects', 'model' => Project::class, 'search' => ['title', 'short_description'], 'image' => 'thumbnail', 'fields' => [
                'title' => 'text', 'slug' => 'text', 'short_description' => 'textarea', 'full_description' => 'textarea', 'problem' => 'textarea', 'solution' => 'textarea', 'features' => 'textarea', 'role' => 'text', 'category' => 'select:Web Development|UI/UX|Graphic Design|Web3|Software Engineering', 'technologies' => 'text', 'thumbnail' => 'image', 'gallery_images' => 'gallery', 'github_url' => 'url', 'live_url' => 'url', 'featured' => 'checkbox', 'status' => 'select:draft|published', 'display_order' => 'number']],
            'skills' => ['title' => 'Skills', 'model' => Skill::class, 'search' => ['name', 'category'], 'fields' => ['name' => 'text', 'category' => 'select:Development|Design|Technology|Professional', 'description' => 'textarea', 'proficiency' => 'number', 'icon' => 'text', 'status' => 'select:active|disabled', 'display_order' => 'number']],
            'services' => ['title' => 'Services', 'model' => Service::class, 'search' => ['title'], 'fields' => ['title' => 'text', 'description' => 'textarea', 'icon' => 'text', 'status' => 'select:active|disabled', 'display_order' => 'number']],
            'designs' => ['title' => 'Design gallery', 'model' => Design::class, 'search' => ['title', 'category'], 'image' => 'image', 'fields' => ['title' => 'text', 'description' => 'textarea', 'category' => 'select:Branding|Social Media|Posters|Digital Design', 'image' => 'image', 'featured' => 'checkbox', 'status' => 'select:draft|published', 'display_order' => 'number']],
            'articles' => ['title' => 'Articles', 'model' => Article::class, 'search' => ['title', 'excerpt'], 'image' => 'featured_image', 'fields' => ['title' => 'text', 'slug' => 'text', 'excerpt' => 'textarea', 'content' => 'textarea', 'featured_image' => 'image', 'category' => 'text', 'status' => 'select:draft|published', 'published_at' => 'datetime-local']],
            default => abort(404),
        };
    }

    public function index(Request $request, string $resource)
    {
        $def = $this->definition($resource); $model = $def['model']; $term = $request->string('q')->trim();
        $items = $model::query()->when($term->isNotEmpty(), function ($query) use ($term, $def) { $query->where(function ($q) use ($term, $def) { foreach ($def['search'] as $field) { $q->orWhere($field, 'like', '%'.$term.'%'); } }); })->latest()->paginate(12)->withQueryString();
        return view('admin.content.index', compact('resource', 'def', 'items'));
    }

    public function create(string $resource) { $def = $this->definition($resource); return view('admin.content.form', compact('resource', 'def') + ['item' => null]); }
    public function edit(string $resource, int $id) { $def = $this->definition($resource); $item = $def['model']::findOrFail($id); return view('admin.content.form', compact('resource', 'def', 'item')); }

    public function store(Request $request, string $resource) { $def = $this->definition($resource); $model = $def['model']; $item = new $model; return $this->persist($request, $resource, $def, $item, 'created'); }
    public function update(Request $request, string $resource, int $id) { $def = $this->definition($resource); $item = $def['model']::findOrFail($id); return $this->persist($request, $resource, $def, $item, 'updated'); }

    private function persist(Request $request, string $resource, array $def, Model $item, string $verb)
    {
        $rules = [];
        foreach ($def['fields'] as $name => $type) {
            $base = explode(':', $type)[0]; $rules[$name] = match ($base) {
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], 'gallery' => ['nullable', 'array', 'max:12'], 'url' => ['nullable', 'url', 'max:2048'], 'number' => ['nullable', 'integer', 'min:0', 'max:100'], 'checkbox' => ['nullable', 'boolean'], 'datetime-local' => ['nullable', 'date'], 'textarea' => ['nullable', 'string'], default => ['nullable', 'string', 'max:1000'],
            };
            if ($base === 'gallery') { $rules[$name.'.*'] = ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120']; }
        }
        foreach (['title', 'name', 'description', 'content', 'short_description'] as $required) { if (isset($rules[$required])) { array_unshift($rules[$required], 'required'); } }
        $data = $request->validate($rules);
        foreach ($def['fields'] as $name => $type) { if (explode(':', $type)[0] === 'checkbox') { $data[$name] = $request->boolean($name); } }
        if (isset($data['slug'])) { $data['slug'] = Str::slug($data['slug'] ?: ($data['title'] ?? '')); if (! $data['slug']) { $data['slug'] = Str::uuid(); } }
        if (isset($data['features'])) { $data['features'] = array_values(array_filter(preg_split('/\r\n|\r|\n/', $data['features'] ?? ''))); }
        if ($resource === 'articles' && ($data['status'] ?? $item->status) === 'published' && empty($data['published_at']) && ! $item->published_at) { $data['published_at'] = now(); }
        if (isset($def['image']) && $request->hasFile($def['image'])) { $data[$def['image']] = $request->file($def['image'])->store('portfolio/'.$resource, 'public'); }
        unset($data['technologies'], $data['gallery_images']); $item->fill($data)->save();
        if ($item instanceof Project) { $this->syncTechnologies($item, $request->input('technologies')); foreach ($request->file('gallery_images', []) as $order => $image) { ProjectImage::create(['project_id' => $item->id, 'image' => $image->store('portfolio/projects/gallery', 'public'), 'display_order' => $item->images()->count() + $order]); } }
        return redirect()->route('admin.content.index', $resource)->with('success', "{$def['title']} {$verb} successfully.");
    }

    private function syncTechnologies(Project $project, ?string $technologies): void
    {
        $ids = collect(explode(',', (string) $technologies))->map(fn ($name) => trim($name))->filter()->map(function ($name) { return Technology::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name])->id; });
        $project->technologies()->sync($ids);
    }

    public function destroy(string $resource, int $id)
    {
        $def = $this->definition($resource); $def['model']::findOrFail($id)->delete();
        return back()->with('success', "{$def['title']} deleted successfully.");
    }
}
