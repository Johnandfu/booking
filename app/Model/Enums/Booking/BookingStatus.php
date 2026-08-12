<?php

declare(strict_types=1);

namespace App\Model\Enums\Booking;

use Hyperf\Constants\Annotation\Constants;
use Hyperf\Constants\Annotation\Message;
use Hyperf\Constants\EnumConstantsTrait;

#[Constants]
enum BookingStatus: string
{
    use EnumConstantsTrait;

    #[Message('booking.enums.status.pending')]
    case Pending = 'pending';

    #[Message('booking.enums.status.confirmed')]
    case Confirmed = 'confirmed';

    #[Message('booking.enums.status.cancelled')]
    case Cancelled = 'cancelled';

    #[Message('booking.enums.status.completed')]
    case Completed = 'completed';

    #[Message('booking.enums.status.no_show')]
    case NoShow = 'no_show';

    #[Message('booking.enums.status.expired')]
    case Expired = 'expired';
}
