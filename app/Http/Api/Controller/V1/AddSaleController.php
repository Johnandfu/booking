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

use App\Factory\Cache;
use App\Factory\Log;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\Common\ResultCode;
use App\Service\AddSaleService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Get;


use Psr\SimpleCache\CacheInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\Logger;
use Hyperf\RateLimit\Annotation\RateLimit;
use Psr\Log\LoggerInterface;

use function Hyperf\Collection\collect;
use function Hyperf\Collection\data_get;

#[HyperfServer(name: 'http')]
final class AddSaleController extends AbstractController
{

    public function __construct(
        protected readonly AddSaleService $service,
        protected readonly CacheInterface $cache,
        protected readonly LoggerInterface $logger

    ) {}
    //清理addsale信息
    #[Post(
        path: '/api/v1/add-sale/clear',
        operationId: 'ApiV1AddSaleClear',
        summary: '清理addsale信息',
        tags: ['api'],
    )]
    #[RateLimit(create: 1, capacity: 1, key: [AbstractController::class, "rateLimitKey"])]
    public function clear(RequestInterface $request): Result
    {

        $token = $request->input('token');
        if ($token != 'tss') {
            return $this->error('同步失败token错误');
        }
        $result = $this->service->getAddSale(0, true);
        if (!$result) {
            return $this->error('同步失败');
        }
        return $this->json(ResultCode::JSON_CODE, $result, '同步成功');
    }

    //获取addsale信息
    #[Get(
        path: '/api/v1/add-sale',
        operationId: 'ApiV1AddSaleList',
        summary: '获取addsale列表',
        tags: ['api'],
    )]
    public function list(RequestInterface $request): Result
    {


        $page_data = $this->getPageParams();
        $site_id = $request->input('site_id') ?: 391;
        $result = $this->service->getAddSale(0, false);
        //限制content 商品返回数量
        $result = collect($result)->map(function ($item, $key) use ($page_data) {
            $item['total'] = count(data_get($item, 'content') ?: []);
            $item['content'] = collect(data_get($item, 'content') ?: [])->take($page_data['limit']);
            return $item;
        })->filter(function ($val) use ($site_id) {
            return data_get($val, 'site_id', 391) == $site_id && data_get($val,'type')==1;
        })->values()->toArray();

        return $this->success($result ?: []);
    }

    //获取addsale信息
    #[Get(
        path: '/api/v1/add-sale/{id}',
        operationId: 'ApiV1AddSaleShow',
        summary: '获取addsale详情',
        tags: ['api'],
    )]
    public function show(int $id): Result
    {
        $id = $id ?: 0;
        $page_data = $this->getPageParams();
        $result = $this->service->getAddSale($id);
        // 每个addsale content开始最多返回
        if ($result) {
            $result['total'] = count(data_get($result, 'content') ?: []);
            $result['content'] = collect(data_get($result, 'content') ?: [])->skip($page_data['offset'])->take($page_data['limit'])->values()->toArray();
        }
        return $this->success($result ?: null);
    }
}
