<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;

class SitemapController extends Controller
{
    protected function resolveLang(string $code): Language
    {
        $lang = Language::active()->firstWhere('code', $code);

        if (!$lang) {
            abort(404);
        }

        return $lang;
    }

    protected function activeLangs()
    {
        return Language::active();
    }

    protected function xmlHeaders(): array
    {
        return [
            'Content-Type' => 'text/xml; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
        ];
    }

    protected function addStylesheet(\DOMDocument $dom): void
    {
        $stylesheet = $dom->createProcessingInstruction(
            'xml-stylesheet',
            'type="text/xsl" href="' . asset('sitemap.xsl') . '"'
        );
        $dom->insertBefore($stylesheet, $dom->documentElement);
    }

    protected function sitemapIndexResponse(array $sitemaps)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $index = $dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'sitemapindex');
        $dom->appendChild($index);
        $this->addStylesheet($dom);

        $lastmod = now()->toAtomString();

        foreach ($sitemaps as $loc) {
            $entry = $dom->createElement('sitemap');
            $index->appendChild($entry);
            $entry->appendChild($dom->createElement('loc', $loc));
            $entry->appendChild($dom->createElement('lastmod', $lastmod));
        }

        return response($dom->saveXML(), 200, $this->xmlHeaders());
    }

    protected function urlsetResponse(array $urls)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
        $dom->appendChild($urlset);
        $this->addStylesheet($dom);

        foreach ($urls as $url) {
            $urlNode = $dom->createElement('url');
            $urlset->appendChild($urlNode);
            $urlNode->appendChild($dom->createElement('loc', $url['loc']));

            if (!empty($url['lastmod'])) {
                $urlNode->appendChild($dom->createElement('lastmod', $url['lastmod']));
            }

            if (!empty($url['changefreq'])) {
                $urlNode->appendChild($dom->createElement('changefreq', $url['changefreq']));
            }

            if (isset($url['priority'])) {
                $urlNode->appendChild($dom->createElement('priority', (string) $url['priority']));
            }
        }

        return response($dom->saveXML(), 200, $this->xmlHeaders());
    }

    protected function langSitemapRoutes(string $lang): array
    {
        return [
            route('sitemap.main', ['lang' => $lang]),
            route('sitemap.pages', ['lang' => $lang]),
            route('sitemap.technologies', ['lang' => $lang]),
            route('sitemap.technology-categories', ['lang' => $lang]),
        ];
    }

    public function robots()
    {
        $body = implode("\n", [
            'User-agent: *',
            'Disallow: /yonetim',
            'Disallow: /yonetim/',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ]);

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function index()
    {
        $sitemaps = [];

        foreach ($this->activeLangs() as $lang) {
            $sitemaps[] = route('sitemap.lang', ['lang' => $lang->code]);
        }

        return $this->sitemapIndexResponse($sitemaps);
    }

    public function langIndex(string $lang)
    {
        $lang = $this->resolveLang($lang)->code;

        return $this->sitemapIndexResponse($this->langSitemapRoutes($lang));
    }

    public function main(string $lang)
    {
        $lang = $this->resolveLang($lang);
        $activeLangs = $this->activeLangs();
        $urls = [];

        $altsHome = [];
        foreach ($activeLangs as $l) {
            $altsHome[] = [
                'hreflang' => $l->code,
                'href' => url($l->code),
            ];
        }
        $urls[] = [
            'loc' => url($lang->code),
            'priority' => '1.0',
            'changefreq' => 'daily',
            'lastmod' => now()->toAtomString(),
            'alternates' => $altsHome,
        ];

        return $this->urlsetResponse($urls);
    }

    public function pages(string $lang)
    {
        $lang = $this->resolveLang($lang);
        $activeLangs = $this->activeLangs();
        $urls = [];

        foreach (Page::all() as $page) {
            if ($page->type === 'index') {
                continue;
            }

            $trans = $page->translate($lang->code);
            if (!$trans || empty($trans->slug) || empty($page->type)) {
                continue;
            }

            $alts = [];
            foreach ($activeLangs as $l) {
                $t = $page->translate($l->code);
                if ($t && !empty($t->slug)) {
                    $alts[] = [
                        'hreflang' => $l->code,
                        'href' => m_url($page->type, null, $l->code),
                    ];
                }
            }

            $urls[] = [
                'loc' => m_url($page->type, null, $lang->code),
                'lastmod' => $page->updated_at ? $page->updated_at->toAtomString() : now()->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'alternates' => $alts,
            ];
        }

        return $this->urlsetResponse($urls);
    }

    public function technologies(string $lang)
    {
        $lang = $this->resolveLang($lang);
        $activeLangs = $this->activeLangs();
        $urls = [];

        foreach (Product::where('status', 1)->get() as $technology) {
            $trans = $technology->translate($lang->code);
            if (!$trans || empty($trans->slug)) {
                continue;
            }

            $alts = [];
            foreach ($activeLangs as $l) {
                $t = $technology->translate($l->code);
                if ($t && !empty($t->slug)) {
                    $alts[] = [
                        'hreflang' => $l->code,
                        'href' => m_url('products', $t->slug, $l->code),
                    ];
                }
            }

            $urls[] = [
                'loc' => m_url('products', $trans->slug, $lang->code),
                'lastmod' => $technology->updated_at ? $technology->updated_at->toAtomString() : now()->toAtomString(),
                'priority' => '0.9',
                'changefreq' => 'weekly',
                'alternates' => $alts,
            ];
        }

        return $this->urlsetResponse($urls);
    }

    public function technologyCategories(string $lang)
    {
        $lang = $this->resolveLang($lang);
        $activeLangs = $this->activeLangs();
        $urls = [];

        foreach (ProductCategory::where('status', 1)->get() as $category) {
            $trans = $category->translate($lang->code);
            if (!$trans || empty($trans->slug)) {
                continue;
            }

            $alts = [];
            foreach ($activeLangs as $l) {
                $t = $category->translate($l->code);
                if ($t && !empty($t->slug)) {
                    $alts[] = [
                        'hreflang' => $l->code,
                        'href' => m_url('products', $t->slug, $l->code),
                    ];
                }
            }

            $urls[] = [
                'loc' => m_url('products', $trans->slug, $lang->code),
                'lastmod' => $category->updated_at ? $category->updated_at->toAtomString() : now()->toAtomString(),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'alternates' => $alts,
            ];
        }

        return $this->urlsetResponse($urls);
    }
}
