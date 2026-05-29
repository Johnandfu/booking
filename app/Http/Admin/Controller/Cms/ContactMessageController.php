<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\ContactMessageRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsContactMessageService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Put;
use Mine\Access\Attribute\Permission;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class ContactMessageController extends AbstractController
{
    public function __construct(
        private readonly CmsContactMessageService $service
    ) {}

    #[Get(path: '/admin/cms/contact-message/list', operationId: 'cmsContactMessageList', summary: 'CMS 留言列表', tags: ['CMS'])]
    #[Permission(code: 'cms:contact-message:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Put(path: '/admin/cms/contact-message/{id}', operationId: 'cmsContactMessageUpdate', summary: '处理 CMS 留言', tags: ['CMS'])]
    #[Permission(code: 'cms:contact-message:update')]
    public function update(int $id, ContactMessageRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/contact-message/{id}', operationId: 'cmsContactMessageDelete', summary: '删除 CMS 留言', tags: ['CMS'])]
    #[Permission(code: 'cms:contact-message:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
