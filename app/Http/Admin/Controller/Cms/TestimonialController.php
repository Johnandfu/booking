<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\TestimonialRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsTestimonialService;
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
final class TestimonialController extends AbstractController
{
    public function __construct(
        private readonly CmsTestimonialService $service
    ) {}

    #[Get(path: '/admin/cms/testimonial/list', operationId: 'cmsTestimonialList', summary: 'CMS 客户反馈列表', tags: ['CMS'])]
    #[Permission(code: 'cms:testimonial:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/cms/testimonial', operationId: 'cmsTestimonialCreate', summary: '创建 CMS 客户反馈', tags: ['CMS'])]
    #[Permission(code: 'cms:testimonial:create')]
    public function create(TestimonialRequest $request): Result
    {
        $this->service->create($request->validated());
        return $this->success();
    }

    #[Put(path: '/admin/cms/testimonial/{id}', operationId: 'cmsTestimonialUpdate', summary: '更新 CMS 客户反馈', tags: ['CMS'])]
    #[Permission(code: 'cms:testimonial:update')]
    public function update(int $id, TestimonialRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/testimonial/{id}', operationId: 'cmsTestimonialDelete', summary: '删除 CMS 客户反馈', tags: ['CMS'])]
    #[Permission(code: 'cms:testimonial:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
