<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */


namespace App\Http\Api\Controller\V1;

use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Schema\UserFilterLogSchema;
use App\Schema\UserLoginLogSchema;
use App\Service\UserFilterLogService;

use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Mine\Swagger\Attributes\PageResponse;

#[HyperfServer(name: 'http')]

final class UserFilterLogController extends AbstractController
{
    public function __construct(
        protected readonly UserFilterLogService $service,

    ) {}

    #[Get(
        path: '/api/v1/user-filter-log/list',
        operationId: 'ApiV1UserFilterList',
        summary: '获取滤镜使用列表',
        tags: ['api'],
    )]
    public function list(): Result
    {

        if ($this->getRequest()->input('token') != 'tss') {
            return $this->error('');
        }
        return $this->success(
            $this->service->page(
                $this->getRequestData(),
                $this->getCurrentPage(),
                $this->getPageSize()
            )
        );
    }
}
