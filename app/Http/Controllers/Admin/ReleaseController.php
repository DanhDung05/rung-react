<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChangelogService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReleaseController extends Controller
{
    public function __construct(private ChangelogService $changelog)
    {
    }

    public function index(Request $request)
    {
        $entries = $this->changelog->entries();

        $groups = [];
        foreach ($entries as $entry) {
            foreach ($entry['groups'] as $group) {
                $groups[$group['key']] = $group['label'];
            }
        }

        $keyword = trim((string) $request->query('q', ''));
        $groupFilter = (string) $request->query('group', '');

        $filtered = array_values(array_filter($entries, function (array $entry) use ($keyword, $groupFilter) {
            if ($groupFilter !== '' && ! collect($entry['groups'])->contains(fn ($group) => $group['key'] === $groupFilter)) {
                return false;
            }

            if ($keyword === '') {
                return true;
            }

            $haystack = $entry['version'] . ' ' . $entry['title'] . ' ' . collect($entry['groups'])
                ->flatMap(fn ($group) => array_merge([$group['label']], $group['items']))
                ->implode(' ');

            return mb_stripos($haystack, $keyword) !== false;
        }));

        return Inertia::render('Admin/Releases/Index', [
            'entries'        => $filtered,
            'groups'         => $groups,
            'currentVersion' => $this->currentVersion(),
            'filters'        => ['q' => $keyword, 'group' => $groupFilter],
        ]);
    }

    public function show(string $version)
    {
        $entry = collect($this->changelog->entries())->firstWhere('version', $version);

        if (! $entry) {
            abort(404);
        }

        return Inertia::render('Admin/Releases/Show', [
            'entry'          => $entry,
            'currentVersion' => $this->currentVersion(),
        ]);
    }

    private function currentVersion(): string
    {
        $path = base_path('VERSION');

        return file_exists($path) ? trim(file_get_contents($path)) : '0.0.0';
    }
}
