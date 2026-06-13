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
        $featureArticles = [];
        $serviceCategory = $this->cmsCategory->serviceCategory();
        if ($serviceCategory !== null) {
            $publicSlug = $this->cmsCategory->publicSlug($serviceCategory);
            $featureArticles = $this->cmsArticle->articlesByCategoryAll($serviceCategory->id)
                ->map(fn($article): array => [
                    'category' => $serviceCategory,
                    'article' => $article,
                    'url' => '/' . $publicSlug . '/' . $article->slug,
                ])
                ->values()
                ->all();
        }

        $galleryArticles = [];
        $galleryCategory = $this->cmsCategory->categoryBySlug('news');
        if ($galleryCategory !== null) {
            $galleryArticles = $this->cmsArticle->latestArticlesByCategory($galleryCategory->id, 6)
                ->map(fn($article): array => [
                    'category' => $galleryCategory,
                    'article' => $article,
                    'url' => '/' . $galleryCategory->slug . '/' . $article->slug,
                ])
                ->values()
                ->all();
        }

        $teamMembers = $this->cmsTeamMember->enabledMembers()
            ->take(20)
            ->map(static fn($member): array => [
                'name' => $member->name,
                'position' => $member->position,
                'phone' => $member->phone,
                'avatar' => $member->avatar,
            ])
            ->values()
            ->all();

        if ($teamMembers === []) {
            $teamMembers = array_map(static fn(string $name): array => [
                'name' => $name,
                'position' => '',
                'phone' => '',
                'avatar' => '',
            ], ['Savannah Nguyen']);
        }

        $testimonials = $this->cmsTestimonial->enabledTestimonials()
            ->take(20)
            ->map(static fn($testimonial): array => [
                'name' => $testimonial->name,
                'position' => $testimonial->position,
                'avatar' => $testimonial->avatar,
                'content' => $testimonial->content,
            ])
            ->values()
            ->all();

        if ($testimonials === []) {
            $testimonials = [
                ['name' => 'Christine Eve', 'position' => 'Founder & CEO', 'avatar' => '', 'content' => 'test'],
            ];
        }

        return $this->view($render, 'index.home', [
            'activeNav' => 'home',
            'pageTitle' => 'home-title',
            'pageDescription' => 'home-description',
            'banners' => $this->cmsBanner->banners(),
            'featureSlides' => array_chunk($featureArticles, 4),
            'featureMobileSlides' => array_chunk($featureArticles, 1),
            'galleryArticles' => $galleryArticles,
            'galleryMobileSlides' => array_chunk($galleryArticles, 1),
            'teamSlides' => array_chunk($teamMembers, 4),
            'teamMobileSlides' => array_chunk($teamMembers, 1),
            'testimonialSlides' => array_chunk($testimonials, 4),
            'testimonialMobileSlides' => array_chunk($testimonials, 1),
        ]);
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
