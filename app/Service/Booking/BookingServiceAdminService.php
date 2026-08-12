<?php

declare(strict_types=1);

namespace App\Service\Booking;

use App\Repository\Booking\BookingServiceRepository;
use App\Service\IService;

/**
 * @extends IService<\App\Model\Booking\BookingService>
 */
final class BookingServiceAdminService extends IService
{
    public function __construct(
        protected readonly BookingServiceRepository $repository
    ) {}
}
