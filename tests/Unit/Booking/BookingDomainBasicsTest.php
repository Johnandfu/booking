<?php

declare(strict_types=1);

namespace HyperfTests\Unit\Booking;

use App\Model\Enums\Booking\BookingStatus;
use App\Model\Enums\Booking\SlotLockStatus;
use App\Model\Enums\Booking\SlotStatus;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class BookingDomainBasicsTest extends TestCase
{
    public function testStatusEnumsMatchProjectContract(): void
    {
        self::assertSame(
            ['pending', 'confirmed', 'cancelled', 'completed', 'no_show', 'expired'],
            array_map(static fn (BookingStatus $c) => $c->value, BookingStatus::cases())
        );
        self::assertSame(
            ['available', 'locked', 'full', 'disabled'],
            array_map(static fn (SlotStatus $c) => $c->value, SlotStatus::cases())
        );
        self::assertSame(
            ['active', 'released', 'consumed', 'expired'],
            array_map(static fn (SlotLockStatus $c) => $c->value, SlotLockStatus::cases())
        );
    }
}
