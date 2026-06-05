<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsArticleService;
use App\Service\Cms\CmsCategoryService;
use App\Service\Cms\CmsLanguageService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class CategoryController extends BaseController
{
    public function __construct(
        CmsLanguageService $cmsLangcode,
        CmsCategoryService $cmsCategory,
        RequestInterface $request,
        protected readonly CmsArticleService $cmsArticle,
    ) {
        parent::__construct($cmsLangcode, $cmsCategory, $request);
    }

    public function index(string $categorySlug, RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        $category = $this->cmsCategory->categoryBySlug($categorySlug);
        if ($category === null) {
            return $this->notFound($render);
        }

        $page = max(1, (int) $request->input('page', 1));
        $name = trim((string) $request->input('name', ''));

        if ($category->slug === 'news') {
            $data = $this->cmsArticle->newsList($category->id, $page, $name);

            return $this->view($render, 'index.news', array_merge($data, [
                'pageTitle' => 'category-' . $category->slug . 'Title',
                'pageDescription' => 'category-' . $category->slug . 'Description',

                'activeNav' => $category->slug,
                'category' => $category,
                'basePath' => '/' . $category->slug,
                'name' => $name,
            ]));
        }

        $data = $this->cmsArticle->serviceList($category->id, $page, $name);

        return $this->view($render, 'index.category', array_merge($data, [
            'pageTitle' => 'category-' . $category->slug . 'Title',
            'pageDescription' => 'category-' . $category->slug . 'Description',

            'activeNav' => $category->slug,
            'category' => $category,
            'basePath' =>  '/' . $category->slug,
            'name' => $name,
            'serviceSections' => $this->serviceSections($data['paginator']->items()),
        ]));
    }

    public function show(string $categorySlug, string $id, RenderInterface $render): ResponseInterface
    {
        $article = $this->cmsArticle->findById((int) $id, ['category:id,slug,name']);
        if ($article === null || $article->category === null) {
            return $this->notFound($render);
        }
        $this->cmsArticle->incrementViews($article->id);
        return $this->view($render, 'index.article', [
            'pageTitleOri' => $article->title,
            'pageDescriptionOri' => $article->summary,
            'activeNav' => $article->category ? $article->category->slug : 'home',
            'article' => $article,
            'category' => $article->category,
        ]);
    }

    private function serviceSections(array $articles): array
    {
        if ($articles !== []) {
            return array_map(static function ($article): array {
                return [
                    'title' => $article->title,
                    'content' => $article->content ?: ($article->summary ? '<p>' . htmlspecialchars((string) $article->summary, ENT_QUOTES, 'UTF-8') . '</p>' : ''),
                    'image' => $article->cover ?: 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=900&q=85',
                ];
            }, $articles);
        }

        return [
            [
                'title' => 'Short-Term Rental Management',
                'content' => '<p>We manage fully furnished short-stay properties across major platforms.</p><p><strong>Services include:</strong></p><ul><li>Property setup, furnishing and interior styling</li><li>Brissy Breeze branded furniture, linen and hospitality essentials</li><li>Listing creation and management across major short-stay platforms including Airbnb</li><li>Co-host services for short-stay properties</li><li>24/7 guest and property support with fast response times</li><li>Guest communication and booking coordination</li><li>Cleaning, maintenance and property care coordination</li><li>Property inspections and condition reporting</li><li>Development Application (DA) support and local council permit coordination</li><li>Financial reporting and performance updates for property owner</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=900&q=85',
            ],
            [
                'title' => 'Leasing Services',
                'content' => '<p>We support landlords with professional long-term rental management.</p><p><strong>Services include:</strong></p><ul><li>Property marketing and advertising</li><li>Tenant screening and selection</li><li>Lease preparation and documentation</li><li>Entry condition reports</li><li>Tenant and landlord communication</li><li>Maintenance coordination</li><li>RTA compliance support</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=900&q=85',
            ],
            [
                'title' => 'Property Sales Support',
                'content' => '<p>For owners considering selling their property, we provide guidance and support throughout the process.</p><p><strong>Services include:</strong></p><ul><li>Property appraisal and market insights</li><li>Sales campaign coordination</li><li>Buyer enquiry management</li><li>Sales process assistance</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=900&q=85',
            ],
            [
                'title' => 'Financial Guidance',
                'content' => '<p>We help property owners understand and optimise their investment performance.</p><p><strong>Services include:</strong></p><ul><li>Rental return analysis</li><li>Property investment insights</li><li>Cash-flow and cost planning</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=900&q=85',
            ],
        ];
    }
}
