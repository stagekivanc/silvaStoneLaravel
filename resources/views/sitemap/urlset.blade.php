{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
@if (!empty($url['alternates']))
@foreach ($url['alternates'] as $alt)
        {!! '<xhtml:link rel="alternate" hreflang="'.e($alt['hreflang']).'" href="'.e($alt['href']).'" />' !!}
@endforeach
@endif
@if (!empty($url['lastmod']))
        <lastmod>{{ $url['lastmod'] }}</lastmod>
@endif
@if (!empty($url['changefreq']))
        <changefreq>{{ $url['changefreq'] }}</changefreq>
@endif
@if (isset($url['priority']))
        <priority>{{ $url['priority'] }}</priority>
@endif
    </url>
@endforeach
</urlset>
