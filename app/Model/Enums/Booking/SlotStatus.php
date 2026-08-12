<?php

declare(strict_types=1);

namespace App\Model\Enums\Booking;

use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\Annotation\Message;
use Hyperf\Constants\EnumConstantsTrait;

#[Constants]
enum SlotStatus: string
{
    use EnumConstantsTrait;

    #[Message('booking.enums.slot_status.available')]
    case Available = 'available';

    #[Message('booking.enums.slot_status.locked')]
    case Locked = 'locked';

    #[Message('booking.enums.slot_status.full')]
    case Full = 'full';

    #[Message('booking.enums.slot_status.disabled')]
    case Disabled = 'disabled';
}
