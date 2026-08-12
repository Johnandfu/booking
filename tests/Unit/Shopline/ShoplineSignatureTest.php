<?php

declare(strict_types=1);

namespace HyperfTests\Unit\Shopline;

use App\Shopline\ShoplineSignature;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ShoplineSignatureTest extends TestCase
{
    private ShoplineSignature $signature;

    private string $secret = 'test-app-secret';

    protected function setUp(): void
    {
        parent::setUp();
        $this->signature = new ShoplineSignature();
    }

    public function testVerifyGetSignature(): void
    {
        $query = [
            'appkey' => 'app-key',
            'handle' => 'demo-store',
            'timestamp' => (string) (int) floor(microtime(true) * 1000),
        ];
        $source = $this->signature->buildGetSource($query);
        $query['sign'] = $this->signature->hmacSha256($source, $this->secret);

        self::assertTrue($this->signature->verifyGet($query, $this->secret, $query['sign']));
    }

    public function testVerifyGetRejectsTamperedParams(): void
    {
        $query = [
            'appkey' => 'app-key',
            'handle' => 'demo-store',
            'timestamp' => (string) (int) floor(microtime(true) * 1000),
        ];
        $source = $this->signature->buildGetSource($query);
        $query['sign'] = $this->signature->hmacSha256($source, $this->secret);
        $query['handle'] = 'other-store';

        self::assertFalse($this->signature->verifyGet($query, $this->secret, $query['sign']));
    }

    public function testSignPostUsesBodyPlusTimestamp(): void
    {
        $body = '{"code":"abc"}';
        $timestamp = '1710000000000';
        $sign = $this->signature->signPost($body, $timestamp, $this->secret);

        self::assertSame(
            $this->signature->hmacSha256($body . $timestamp, $this->secret),
            $sign
        );
    }

    public function testTimestampFreshness(): void
    {
        $nowMs = (string) (int) floor(microtime(true) * 1000);
        self::assertTrue($this->signature->isTimestampFresh($nowMs));
        self::assertFalse($this->signature->isTimestampFresh((string) ((time() - 1200) * 1000)));
    }

    public function testVerifyWebhookHexAndBase64(): void
    {
        $body = '{"id":"ORDER1","note_attributes":[{"name":"booking_no","value":"B1"}]}';
        $hex = hash_hmac('sha256', $body, $this->secret);
        $b64 = base64_encode(hash_hmac('sha256', $body, $this->secret, true));

        self::assertTrue($this->signature->verifyWebhook($body, $hex, $this->secret));
        self::assertTrue($this->signature->verifyWebhook($body, $b64, $this->secret));
        self::assertFalse($this->signature->verifyWebhook($body, 'tampered', $this->secret));
    }
}
