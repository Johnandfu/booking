<?php

declare(strict_types=1);

namespace App\Service\Booking;

use App\Repository\Booking\BookingScheduleRepository;
use App\Service\IService;

/**
 * @extends IService<\App\Model\Booking\BookingSchedule>
 */
final class BookingScheduleAdminService extends IService
{
    public function __construct(
        protected readonly BookingScheduleRepository $repository
    ) {}
}
