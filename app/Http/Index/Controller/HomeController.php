<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsContactMessageService;
use App\Service\Cms\CmsCategoryService;
use App\Service\Cms\CmsLanguageService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class HomeController extends BaseController
{
    public function __construct(
        CmsLanguageService $cmsLangcode,
        CmsCategoryService $cmsCategory,
        RequestInterface $request,
        protected readonly CmsContactMessageService $cmsContactMessage,
    ) {
        parent::__construct($cmsLangcode, $cmsCategory, $request);
    }

    public function index(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.home', [
            'activeNav' => 'home',
        ]);
    }

    public function about(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.about', [
            'title' => 'main-aboutTitle',
            'activeNav' => 'about',
        ]);
    }

    public function contact(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.contact', [
            'title' => 'contact-title',
            'activeNav' => 'contact',
            'message' => null,
            'messageType' => 'success',
        ]);
    }

    public function submitContact(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        $name = trim((string) $request->input('name', ''));
        $contentValue = trim((string) $request->input('content', ''));

        if ($name === '' || $contentValue === '') {
            return $this->view($render, 'index.contact', [
                'title' => 'contact-title',
                'activeNav' => 'contact',
                'message' => 'contact-message',
                'messageType' => 'danger',
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

        return $this->view($render, 'index.contact', [
            'title' => 'contact-title',
            'activeNav' => 'contact',
            'message' => 'contact-message',
            'messageType' => 'success',
        ]);
    }
}
