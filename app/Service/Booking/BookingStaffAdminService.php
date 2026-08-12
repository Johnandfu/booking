<?php

declare(strict_types=1);

namespace App\Service\Booking;

use App\Repository\Booking\BookingStaffRepository;
use App\Service\IService;

/**
 * @extends IService<\App\Model\Booking\BookingStaff>
 */
final class BookingStaffAdminService extends IService
{
    public function __construct(
        protected readonly BookingStaffRepository $repository
    ) {}
}
