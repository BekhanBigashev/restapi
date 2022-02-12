<?php

declare(strict_types=1);

namespace App\Application\Actions\Schedule;

use App\Application\Actions\Action;
use App\Domain\Schedule\ScheduleRepository;
use Psr\Log\LoggerInterface;

abstract class ScheduleAction extends Action
{
    protected ScheduleRepository $scheduleRepository;

    public function __construct(LoggerInterface $logger, ScheduleRepository $scheduleRepository)
    {
        parent::__construct($logger);
        $this->scheduleRepository = $scheduleRepository;
    }
}
