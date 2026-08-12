<?php

declare(strict_types=1);

namespace App\Service\Booking;

use App\Repository\Booking\BookingLocationRepository;
use App\Service\IService;

/**
 * @extends IService<\App\Model\Booking\BookingLocation>
 */
final class BookingLocationAdminService extends IService
{
    public function __construct(
        protected readonly BookingLocationRepository $repository
    ) {}
}
