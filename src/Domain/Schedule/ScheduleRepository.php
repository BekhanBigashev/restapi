<?php

declare(strict_types=1);

namespace App\Domain\Schedule;

interface ScheduleRepository
{
    public function opened();
    public function closed();
}
