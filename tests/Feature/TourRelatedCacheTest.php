<?php

namespace Tests\Feature;

use App\Helpers\CacheHelper;
use App\Models\Category;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class TourRelatedCacheTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create(['name' => 'Trekking', 'slug' => 'trekking', 'type' => 'tour', 'is_active' => true]);
    }

    private function tour(Category $category, string $name, string $slug, string $status = 'published'): Tour
    {
        return Tour::create([
            'category_id'  => $category->id,
            'name'         => $name,
            'slug'         => $slug,
            'status'       => $status,
            'published_at' => $status === 'published' ? now() : null,
        ]);
    }

    private function relatedIds(Tour $tour): array
    {
        $props = $this->get(route('tours.show', $tour->slug))->assertOk()->viewData('page')['props'];

        return collect($props['relatedTours'])->pluck('id')->all();
    }

    public function test_related_never_contains_current_tour(): void
    {
        $category = $this->category();
        $a = $this->tour($category, 'Tour A', 'tour-a');
        $b = $this->tour($category, 'Tour B', 'tour-b');

        $this->assertNotContains($a->id, $this->relatedIds($a));
        $this->assertNotContains($b->id, $this->relatedIds($b));
    }

    public function test_related_excludes_draft_tours_and_uses_per_tour_cache(): void
    {
        $category = $this->category();
        $a = $this->tour($category, 'Tour A', 'tour-a');
        $b = $this->tour($category, 'Tour B', 'tour-b');
        $draft = $this->tour($category, 'Tour Nháp', 'tour-nhap', 'draft');

        $ids = $this->relatedIds($a);

        $this->assertContains($b->id, $ids);
        $this->assertNotContains($draft->id, $ids);
        $this->assertTrue(Cache::has("tour_related_{$a->id}"));
        $this->assertFalse(Cache::has("tour_related_{$b->id}"));
    }

    public function test_invalidating_tour_cache_removes_it_from_same_category_related(): void
    {
        $category = $this->category();
        $a = $this->tour($category, 'Tour A', 'tour-a');
        $b = $this->tour($category, 'Tour B', 'tour-b');

        $this->assertContains($b->id, $this->relatedIds($a));

        $b->update(['status' => 'draft', 'published_at' => null]);
        CacheHelper::clearTourRelated($b->id, $b->category_id);

        $this->assertNotContains($b->id, $this->relatedIds($a));
    }
}
