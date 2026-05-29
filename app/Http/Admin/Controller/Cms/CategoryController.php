<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\CategoryRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsCategoryService;
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
final class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CmsCategoryService $service
    ) {}

    #[Get(path: '/admin/cms/category/list', operationId: 'cmsCategoryList', summary: 'CMS 栏目列表', tags: ['CMS'])]
    #[Permission(code: 'cms:category:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/cms/category', operationId: 'cmsCategoryCreate', summary: '创建 CMS 栏目', tags: ['CMS'])]
    #[Permission(code: 'cms:category:create')]
    public function create(CategoryRequest $request): Result
    {
        $this->service->create($request->validated());
        return $this->success();
    }

    #[Put(path: '/admin/cms/category/{id}', operationId: 'cmsCategoryUpdate', summary: '更新 CMS 栏目', tags: ['CMS'])]
    #[Permission(code: 'cms:category:update')]
    public function update(int $id, CategoryRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/category/{id}', operationId: 'cmsCategoryDelete', summary: '删除 CMS 栏目', tags: ['CMS'])]
    #[Permission(code: 'cms:category:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
