<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\PageRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsPageService;
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
final class PageController extends AbstractController
{
    public function __construct(
        private readonly CmsPageService $service
    ) {}

    #[Get(path: '/admin/cms/page/list', operationId: 'cmsPageList', summary: 'CMS 单页列表', tags: ['CMS'])]
    #[Permission(code: 'cms:page:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/cms/page', operationId: 'cmsPageCreate', summary: '创建 CMS 单页', tags: ['CMS'])]
    #[Permission(code: 'cms:page:create')]
    public function create(PageRequest $request): Result
    {
        $this->service->create($request->validated());
        return $this->success();
    }

    #[Put(path: '/admin/cms/page/{id}', operationId: 'cmsPageUpdate', summary: '更新 CMS 单页', tags: ['CMS'])]
    #[Permission(code: 'cms:page:update')]
    public function update(int $id, PageRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/page/{id}', operationId: 'cmsPageDelete', summary: '删除 CMS 单页', tags: ['CMS'])]
    #[Permission(code: 'cms:page:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
