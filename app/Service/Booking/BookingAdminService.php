<?php

declare(strict_types=1);

namespace App\Service\Booking;

use App\Repository\Booking\BookingRepository;
use App\Service\IService;

/**
 * @extends IService<\App\Model\Booking\Booking>
 */
final class BookingAdminService extends IService
{
    public function __construct(
        protected readonly BookingRepository $repository
    ) {}
}
