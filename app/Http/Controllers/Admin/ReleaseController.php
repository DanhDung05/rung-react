<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Release;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ReleaseController extends Controller
{
    private function baseRules(): array
    {
        return [
            'version'          => ['required', 'string', 'max:32', 'regex:/^v?\d+\.\d+\.\d+$/'],
            'previous_version' => ['nullable', 'string', 'max:32'],
            'title'            => ['required', 'string', 'max:255'],
            'category'         => ['required', Rule::in(array_keys(Release::CATEGORIES))],
            'status'           => ['required', Rule::in(array_keys(Release::STATUSES))],
            'content'          => ['required', 'string'],
            'released_at'      => ['nullable', 'date'],
        ];
    }

    public function index(Request $request)
    {
        $query = Release::query()->with('author:id,name');

        if ($request->filled('version')) {
            $query->where('version', 'like', '%' . $request->string('version') . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('released_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('released_at', '<=', $request->date('date_to'));
        }

        $releases = $query
            ->orderByDesc('released_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Releases/Index', [
            'releases'   => $releases,
            'categories' => Release::CATEGORIES,
            'statuses'   => Release::STATUSES,
            'currentVersion' => $this->currentVersion(),
            'filters'    => $request->only(['version', 'category', 'status', 'date_from', 'date_to']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Releases/Form', [
            'release'    => null,
            'categories' => Release::CATEGORIES,
            'statuses'   => Release::STATUSES,
            'currentVersion' => $this->currentVersion(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = $this->baseRules();
        $rules['version'][] = 'unique:releases,version';
        $data = $request->validate($rules);

        $data['created_by'] = $request->user()?->id;

        Release::create($data);

        return redirect()->route('admin.releases.index')->with('success', 'Đã thêm bản cập nhật.');
    }

    public function show(Release $release)
    {
        $release->load('author:id,name');

        return Inertia::render('Admin/Releases/Show', [
            'release' => $release,
            'currentVersion' => $this->currentVersion(),
        ]);
    }

    public function edit(Release $release)
    {
        return Inertia::render('Admin/Releases/Form', [
            'release'    => $release,
            'categories' => Release::CATEGORIES,
            'statuses'   => Release::STATUSES,
            'currentVersion' => $this->currentVersion(),
        ]);
    }

    public function update(Request $request, Release $release)
    {
        $rules = $this->baseRules();
        $rules['version'][] = Rule::unique('releases', 'version')->ignore($release->id);
        $data = $request->validate($rules);

        $release->update($data);

        return redirect()->route('admin.releases.show', $release)->with('success', 'Đã cập nhật bản cập nhật.');
    }

    public function destroy(Release $release)
    {
        $release->delete();

        return redirect()->route('admin.releases.index')->with('success', 'Đã xóa bản cập nhật.');
    }

    private function currentVersion(): string
    {
        $path = base_path('VERSION');

        return file_exists($path) ? trim(file_get_contents($path)) : '0.0.0';
    }
}
