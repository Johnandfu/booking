<?php

declare(strict_types=1);

namespace App\Model\Enums\Booking;

use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\Annotation\Message;
use Hyperf\Constants\EnumConstantsTrait;

#[Constants]
enum SlotLockStatus: string
{
    use EnumConstantsTrait;

    #[Message('booking.enums.slot_lock_status.active')]
    case Active = 'active';

    #[Message('booking.enums.slot_lock_status.released')]
    case Released = 'released';

    #[Message('booking.enums.slot_lock_status.consumed')]
    case Consumed = 'consumed';

    #[Message('booking.enums.slot_lock_status.expired')]
    case Expired = 'expired';
}
