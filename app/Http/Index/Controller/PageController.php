<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

final class PageController extends BaseController
{
    public function show(string $slug, RenderInterface $render): ResponseInterface
    {
        $page = $this->cmsContent->pageBySlug($slug);
        if ($page === null) {
            return $this->notFound($render);
        }

        return $this->view($render, 'index.page', [
            'title' => $page->seo_title ?: $page->title,
            'description' => $page->seo_description,
            'activeNav' => null,
            'page' => $page,
        ]);
    }
}
