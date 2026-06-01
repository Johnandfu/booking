<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsContactMessageService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class HomeController extends BaseController
{
    #[Inject]
    protected CmsContactMessageService $cmsContactMessage;

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
            'title' => '联系我们',
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
                'title' => '联系我们',
                'activeNav' => 'contact',
                'message' => '请填写姓名和留言内容。',
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
            'title' => '联系我们',
            'activeNav' => 'contact',
            'message' => '留言已提交，我们会尽快联系你。',
            'messageType' => 'success',
        ]);
    }
}
