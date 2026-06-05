<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\FrontendI18nRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsContentService;
use App\Service\Cms\CmsLanguageService;
use Hyperf\Collection\Arr;
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
final class FrontendI18nController extends AbstractController
{
    public function __construct(
        private readonly CmsLanguageService $service
    ) {}

    #[Get(path: '/admin/cms/frontend-i18n/list', operationId: 'cmsFrontendI18nList', summary: 'CMS 前台多语言配置列表', tags: ['CMS'])]
    #[Permission(code: 'cms:frontend-i18n:list')]
    public function list(): Result
    {
        return $this->success($this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Get(path: '/admin/cms/frontend-i18n/languages', operationId: 'cmsFrontendI18nLanguages', summary: 'CMS 前台语言列表', tags: ['CMS'])]
    #[Permission(code: 'cms:frontend-i18n:list')]
    public function languages(): Result
    {
        return $this->success(CmsContentService::FRONTEND_LOCALES);
    }

    #[Post(path: '/admin/cms/frontend-i18n', operationId: 'cmsFrontendI18nCreate', summary: '创建 CMS 前台多语言配置', tags: ['CMS'])]
    #[Permission(code: 'cms:frontend-i18n:create')]
    public function create(FrontendI18nRequest $request): Result
    {
        $this->service->updateOrCreate(Arr::only($request->validated(), ['language_code', 'key', 'group_type']), Arr::except($request->validated(), ['language_code', 'key', 'group_type']));
        return $this->success();
    }

    #[Put(path: '/admin/cms/frontend-i18n/{id}', operationId: 'cmsFrontendI18nUpdate', summary: '更新 CMS 前台多语言配置', tags: ['CMS'])]
    #[Permission(code: 'cms:frontend-i18n:update')]
    public function update(int $id, FrontendI18nRequest $request): Result
    {
        $this->service->updateById($id, $request->validated());
        return $this->success();
    }

    #[Delete(path: '/admin/cms/frontend-i18n/{id}', operationId: 'cmsFrontendI18nDelete', summary: '删除 CMS 前台多语言配置', tags: ['CMS'])]
    #[Permission(code: 'cms:frontend-i18n:delete')]
    public function delete(int $id): Result
    {
        $this->service->deleteById($id);
        return $this->success();
    }
}
