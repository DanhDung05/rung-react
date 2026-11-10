@if (!empty($items))
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => collect($items)->map(function ($item, $index) {
        return [
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'name'     => $item['name'] ?? '',
            'item'     => $item['url'] ?? '',
        ];
    })->values()->toArray(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif