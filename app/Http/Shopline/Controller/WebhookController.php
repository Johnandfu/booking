<?php

declare(strict_types=1);

namespace App\Http\Shopline\Controller;

use App\Exception\BusinessException;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\ResultCode;
use App\Service\Shopline\ShoplineWebhookService;
use App\Shopline\Exception\ShoplineSignatureException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Swagger\Annotation as OA;
use Hyperf\Swagger\Annotation\Post;
use Psr\Http\Message\ResponseInterface;

/**
 * SHOPLINE Webhook 接收。
 *
 * 成功须返回 HTTP 200；重复投递须幂等。
 * https://developer.shopline.com/docs/apps/api-instructions-for-use/webhooks/overview
 */
#[OA\HyperfServer(name: 'http')]
final class WebhookController extends AbstractController
{
    public function __construct(
        private readonly ShoplineWebhookService $webhookService,
    ) {}

    #[Post(
        path: '/shopline/webhook',
        operationId: 'shoplineWebhook',
        summary: 'SHOPLINE Webhook 回调',
        tags: ['shopline:webhook'],
    )]
    public function receive(RequestInterface $request, HttpResponse $response): ResponseInterface
    {
        $rawBody = (string) $request->getBody();
        $headers = [];
        foreach (array_keys($request->getHeaders()) as $name) {
            $headers[(string) $name] = $request->getHeaderLine((string) $name);
        }

        try {
            $this->webhookService->handle($rawBody, $headers);
        } catch (ShoplineSignatureException $e) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, $e->getMessage());
        }

        return $response->withStatus(200)->withBody(new SwooleStream('OK'));
    }
}
