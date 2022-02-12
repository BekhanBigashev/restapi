<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Domain\Schedule\ScheduleRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Persistence\Schedule\DBScheduleRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        ScheduleRepository::class => \DI\autowire(DBScheduleRepository::class),
    ]);
};
