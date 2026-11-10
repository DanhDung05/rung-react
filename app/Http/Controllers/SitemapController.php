<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tour;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Sinh sitemap.xml động.
     */
    public function index(): Response
    {
        $urls = [];

        // Trang chủ
        $urls[] = [
            'loc'        => route('home'),
            'lastmod'    => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority'   => '1.0',
        ];

        // Các trang tĩnh chính
        $staticPages = [
            ['route' => 'tours.index',        'priority' => '0.9', 'changefreq' => 'daily'],
            ['route' => 'destinations.index', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['route' => 'posts.index',        'priority' => '0.8', 'changefreq' => 'daily'],
            ['route' => 'about',              'priority' => '0.6', 'changefreq' => 'monthly'],
            ['route' => 'contact',            'priority' => '0.6', 'changefreq' => 'monthly'],
            ['route' => 'faq',                'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc'        => route($page['route']),
                'lastmod'    => now()->toAtomString(),
                'changefreq' => $page['changefreq'],
                'priority'   => $page['priority'],
            ];
        }

        // Tours
        Tour::published()
            ->select('slug', 'updated_at')
            ->chunk(100, function ($tours) use (&$urls) {
                foreach ($tours as $tour) {
                    $urls[] = [
                        'loc'        => route('tours.show', $tour->slug),
                        'lastmod'    => $tour->updated_at->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.8',
                    ];
                }
            });

        // Destinations
        Destination::published()
            ->select('slug', 'updated_at')
            ->chunk(100, function ($destinations) use (&$urls) {
                foreach ($destinations as $destination) {
                    $urls[] = [
                        'loc'        => route('destinations.show', $destination->slug),
                        'lastmod'    => $destination->updated_at->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.7',
                    ];
                }
            });

        // Posts
        Post::published()
            ->select('slug', 'updated_at')
            ->chunk(100, function ($posts) use (&$urls) {
                foreach ($posts as $post) {
                    $urls[] = [
                        'loc'        => route('posts.show', $post->slug),
                        'lastmod'    => $post->updated_at->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.7',
                    ];
                }
            });

        // Pages
        Page::published()
            ->select('slug', 'updated_at')
            ->chunk(100, function ($pages) use (&$urls) {
                foreach ($pages as $page) {
                    $urls[] = [
                        'loc'        => route('pages.show', $page->slug),
                        'lastmod'    => $page->updated_at->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority'   => '0.5',
                    ];
                }
            });

        // Sinh XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}