<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsBannerService;
use App\Service\Cms\CmsArticleService;
use App\Service\Cms\CmsContactMessageService;
use App\Service\Cms\CmsCategoryService;
use App\Service\Cms\CmsLanguageService;
use App\Service\Cms\CmsTeamMemberService;
use App\Service\Cms\CmsTestimonialService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponseInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class HomeController extends BaseController
{
    public function __construct(
        CmsLanguageService $cmsLangcode,
        CmsCategoryService $cmsCategory,
        RequestInterface $request,
        protected readonly CmsBannerService $cmsBanner,
        protected readonly CmsArticleService $cmsArticle,
        protected readonly CmsContactMessageService $cmsContactMessage,
        protected readonly CmsTeamMemberService $cmsTeamMember,
        protected readonly CmsTestimonialService $cmsTestimonial,
    ) {
        parent::__construct($cmsLangcode, $cmsCategory, $request);
    }

    public function index(RenderInterface $render): ResponseInterface
    {
        $teamMembers = $this->teamMembers();
        $testimonials = $this->testimonials();
        $galleryArticles = $this->articleCardsByCategorySlug('news', 6);

        return $this->view($render, 'index.home', [
            'activeNav' => 'home',
            'pageTitle' => 'home-title',
            'pageDescription' => 'home-description',
            'banners' => $this->cmsBanner->banners(),
            'featureSlides' => $this->serviceFeatureSlides(),
            'featureMobileSlides' => $this->serviceFeatureSlides(1),
            'galleryArticles' => $galleryArticles,
            'galleryMobileSlides' => array_chunk($galleryArticles, 1),
            'teamSlides' => array_chunk($teamMembers, 4),
            'teamMobileSlides' => array_chunk($teamMembers, 1),
            'testimonialSlides' => array_chunk($testimonials, 4),
            'testimonialMobileSlides' => array_chunk($testimonials, 1),
        ]);
    }

    private function serviceFeatureSlides(int $perSlide = 4): array
    {
        $category = $this->cmsCategory->serviceCategory();
        if ($category === null) {
            return [];
        }

        $publicSlug = $this->cmsCategory->publicSlug($category);
        $articles = $this->cmsArticle->articlesByCategoryAll($category->id)
            ->map(fn ($article): array => [
                'category' => $category,
                'article' => $article,
                'url' => '/' . $publicSlug . '/' . $article->id,
            ])
            ->values()
            ->all();

        return array_chunk($articles, $perSlide);
    }

    private function articleCardsByCategorySlug(string $slug, int $limit): array
    {
        $category = $this->cmsCategory->categoryBySlug($slug);
        if ($category === null) {
            return [];
        }

        return $this->cmsArticle->latestArticlesByCategory($category->id, $limit)
            ->map(static fn ($article): array => [
                'category' => $category,
                'article' => $article,
                'url' => '/' . $category->slug . '/' . $article->id,
            ])
            ->values()
            ->all();
    }

    private function teamSlides(): array
    {
        return array_chunk($this->teamMembers(), 4);
    }

    private function teamMembers(): array
    {
        $members = $this->cmsTeamMember->enabledMembers()
            ->take(20)
            ->map(static fn ($member): array => [
                'name' => $member->name,
                'position' => $member->position,
                'phone' => $member->phone,
                'avatar' => $member->avatar,
            ])
            ->values()
            ->all();

        if ($members === []) {
            $members = array_map(static fn (string $name): array => [
                'name' => $name,
                'position' => '',
                'phone' => '',
                'avatar' => '',
            ], ['Savannah Nguyen', 'Annette Black', 'Kathryn Murphy', 'David Hardson', 'Fred Andrew']);
        }

        return $members;
    }

    private function testimonialSlides(): array
    {
        return array_chunk($this->testimonials(), 4);
    }

    private function testimonials(): array
    {
        $testimonials = $this->cmsTestimonial->enabledTestimonials()
            ->take(20)
            ->map(static fn ($testimonial): array => [
                'name' => $testimonial->name,
                'position' => $testimonial->position,
                'avatar' => $testimonial->avatar,
                'content' => $testimonial->content,
            ])
            ->values()
            ->all();

        if ($testimonials === []) {
            $testimonials = [
                ['name' => 'Christine Eve', 'position' => 'Founder & CEO', 'avatar' => '', 'content' => ''],
                ['name' => 'Kevin Smith', 'position' => 'Company Founder', 'avatar' => '', 'content' => ''],
                ['name' => 'Jessica Brown', 'position' => 'Founder & CEO', 'avatar' => '', 'content' => ''],
                ['name' => 'David Anderson', 'position' => 'Customer', 'avatar' => '', 'content' => ''],
            ];
        }

        return $testimonials;
    }

    public function about(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.about', [
            'pageTitle' => 'about-title',
            'pageDescription' => 'about-description',
            'activeNav' => 'about',
        ]);
    }

    public function contact(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.contact', [
            'pageTitle' => 'contact-title',
            'pageDescription' => 'contact-description',
            'activeNav' => 'contact',
        ]);
    }

    public function submitContact(RequestInterface $request, HttpResponseInterface $response): ResponseInterface
    {
        $name = trim((string) $request->input('name', ''));
        $contentValue = trim((string) $request->input('content', ''));

        if ($name === '' || $contentValue === '') {
            return $response->json([
                'success' => false,
                'message' => 'contact-submitRequired',
            ]);
        }

        $this->cmsContactMessage->createContactMessage([
            'name' => $name,
            'email' => (string) $request->input('email', ''),
            'phone' => (string) $request->input('phone', ''),
            'content' => $contentValue,
            'ip' => (string) ($request->header('x-real-ip') ?? $request->server('remote_addr') ?? ''),
            'user_agent' => (string) $request->header('user-agent', ''),
        ]);

        return $response->json([
            'success' => true,
            'message' => 'contact-submitSuccess',
        ]);
    }
}
