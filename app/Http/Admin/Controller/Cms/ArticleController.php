<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\ArticleRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsArticleService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Mine\Access\Attribute\Permission;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class ArticleController extends AbstractController
{
    public function __construct(
        private readonly CmsArticleService $service
    ) {}

    #[Get(path: '/admin/cms/article/list', operationId: 'cmsArticleList', summary: 'CMS 文章列表', tags: ['CMS'])]
    #[Permission(code: 'cms:article:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/cms/article', operationId: 'cmsArticleCreate', summary: '创建 CMS 文章', tags: ['CMS'])]
    #[Permission(code: 'cms:article:create')]
    public function create(ArticleRequest $request): Result
    {
        $this->service->create($request->validated());
        return $this->success();
    }

    #[Put(path: '/admin/cms/article/{id}', operationId: 'cmsArticleUpdate', summary: '更新 CMS 文章', tags: ['CMS'])]
    #[Permission(code: 'cms:article:update')]
    public function update(int $id, ArticleRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/article/{id}', operationId: 'cmsArticleDelete', summary: '删除 CMS 文章', tags: ['CMS'])]
    #[Permission(code: 'cms:article:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
