<?php

declare(strict_types=1);

namespace App\Application\Actions\Schedule;

use Psr\Http\Message\ResponseInterface as Response;

class ClosedScheduleAction extends ScheduleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $schedules = $this->scheduleRepository->closed();
        return $this->respondWithData($schedules);
    }
}
