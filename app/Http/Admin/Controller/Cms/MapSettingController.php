<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Cms;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Admin\Request\Cms\MapSettingRequest;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Service\Cms\CmsMapSettingService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Mine\Access\Attribute\Permission;
use Throwable;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class MapSettingController extends AbstractController
{
    public function __construct(
        private readonly CmsMapSettingService $service
    ) {}

    #[Get(path: '/admin/cms/map-setting', operationId: 'cmsMapSettingDetail', summary: 'CMS 地图配置', tags: ['CMS'])]
    #[Permission(code: 'cms:map-setting:list')]
    public function detail(): Result
    {
        return $this->success($this->service->setting());
    }

    #[Put(path: '/admin/cms/map-setting', operationId: 'cmsMapSettingUpdate', summary: '更新 CMS 地图配置', tags: ['CMS'])]
    #[Permission(code: 'cms:map-setting:update')]
    public function update(MapSettingRequest $request): Result
    {
        $this->service->saveSetting($request->validated());
        return $this->success();
    }

    #[Post(path: '/admin/cms/map-setting/geocode', operationId: 'cmsMapSettingGeocode', summary: '解析 CMS 地图地址坐标', tags: ['CMS'])]
    #[Permission(code: 'cms:map-setting:update')]
    public function geocode(): Result
    {
        $address = trim((string) $this->getRequest()->input('address', ''));
        if ($address === '') {
            return $this->error('请输入地址');
        }

        try {
            return $this->success($this->service->geocode($address));
        } catch (Throwable $throwable) {
            return $this->error($throwable->getMessage());
        }
    }
}
