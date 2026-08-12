<?php

declare(strict_types=1);

namespace HyperfTests\Feature\Shopline;

use App\Model\Shop;
use App\Model\ShopToken;
use App\Repository\ShopRepository;
use App\Repository\ShopTokenRepository;
use App\Service\Shopline\ShoplineOAuthService;
use App\Shopline\Client\ShoplineOAuthClient;
use App\Shopline\Dto\AccessTokenResult;
use App\Shopline\ShoplineSignature;
use Carbon\Carbon;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\Redis;
use HyperfTests\HttpTestCase;
use Mockery;

/**
 * @internal
 * @coversNothing
 */
final class ShoplineOAuthServiceTest extends HttpTestCase
{
    private string $secret = 'unit-test-secret';

    private string $appKey = 'unit-test-appkey';

    protected function setUp(): void
    {
        parent::setUp();
        ShopToken::query()->delete();
        Shop::query()->delete();

        $config = ApplicationContext::getContainer()->get(ConfigInterface::class);
        $config->set('shopline.app_key', $this->appKey);
        $config->set('shopline.app_secret', $this->secret);
        $config->set('shopline.oauth_redirect_uri', 'https://example.test/shopline/oauth/callback');
        $config->set('shopline.scopes', 'read_products,read_orders');
        $config->set('shopline.default_timezone', 'UTC');
        $config->set('shopline.embedded', true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHandleOAuthCallbackIsIdempotent(): void
    {
        $signature = new ShoplineSignature();
        $query = [
            'appkey' => $this->appKey,
            'code' => 'auth-code-1',
            'handle' => 'demo-shop',
            'lang' => 'en',
            'timestamp' => (string) (int) floor(microtime(true) * 1000),
        ];
        $query['sign'] = $signature->hmacSha256($signature->buildGetSource($query), $this->secret);

        $client = Mockery::mock(ShoplineOAuthClient::class);
        $client->shouldReceive('createAccessToken')
            ->twice()
            ->with('demo-shop', 'auth-code-1')
            ->andReturn(new AccessTokenResult(
                accessToken: 'token-value-1',
                expireTime: Carbon::now('UTC')->addHours(10)->toIso8601String(),
                scope: 'read_products,read_orders',
            ));

        $container = ApplicationContext::getContainer();
        $service = new ShoplineOAuthService(
            $signature,
            $client,
            $container->get(ShopRepository::class),
            $container->get(ShopTokenRepository::class),
            $container->get(Redis::class),
            $container->get(LoggerFactory::class),
        );

        $first = $service->handleOAuthCallback($query);
        $second = $service->handleOAuthCallback($query);

        self::assertSame($first['shop_id'], $second['shop_id']);
        self::assertSame(1, Shop::query()->where('handle', 'demo-shop')->count());
        self::assertSame(1, ShopToken::query()->where('shop_id', $first['shop_id'])->count());

        $token = ShopToken::query()->where('shop_id', $first['shop_id'])->first();
        self::assertNotNull($token);
        self::assertSame('token-value-1', $token->getAttribute('access_token'));

        $entryQuery = [
            'appkey' => $this->appKey,
            'handle' => 'demo-shop',
            'lang' => 'en',
            'timestamp' => (string) (int) floor(microtime(true) * 1000),
        ];
        $entryQuery['sign'] = $signature->hmacSha256($signature->buildGetSource($entryQuery), $this->secret);
        $entry = $service->resolveEmbeddedEntry($entryQuery);
        self::assertTrue($entry['authorized']);
        self::assertSame($first['shop_id'], $entry['shop_id']);
    }
}
