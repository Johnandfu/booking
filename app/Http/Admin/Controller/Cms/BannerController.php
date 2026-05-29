<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\BannerRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsBannerService;
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
final class BannerController extends AbstractController
{
    public function __construct(
        private readonly CmsBannerService $service
    ) {}

    #[Get(path: '/admin/cms/banner/list', operationId: 'cmsBannerList', summary: 'CMS 轮播列表', tags: ['CMS'])]
    #[Permission(code: 'cms:banner:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/cms/banner', operationId: 'cmsBannerCreate', summary: '创建 CMS 轮播', tags: ['CMS'])]
    #[Permission(code: 'cms:banner:create')]
    public function create(BannerRequest $request): Result
    {
        $this->service->create($request->validated());
        return $this->success();
    }

    #[Put(path: '/admin/cms/banner/{id}', operationId: 'cmsBannerUpdate', summary: '更新 CMS 轮播', tags: ['CMS'])]
    #[Permission(code: 'cms:banner:update')]
    public function update(int $id, BannerRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/banner/{id}', operationId: 'cmsBannerDelete', summary: '删除 CMS 轮播', tags: ['CMS'])]
    #[Permission(code: 'cms:banner:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
