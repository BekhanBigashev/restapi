<?php

declare(strict_types=1);

namespace App\Application\Actions\Schedule;

use Psr\Http\Message\ResponseInterface as Response;

class OpenedScheduleAction extends ScheduleAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $schedules = $this->scheduleRepository->opened();
        return $this->respondWithData($schedules);
    }
}
