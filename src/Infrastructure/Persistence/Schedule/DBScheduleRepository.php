<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Schedule;

use App\Domain\Schedule\Schedule;
use App\Domain\Schedule\ScheduleRepository;

class DBScheduleRepository implements ScheduleRepository
{
    /**
     * @var Schedule[]
     */
    private array $schedule;
    private string $currentTime;
    private int $currentWeek;
    private const HOURS_OFFSET = 21600;
    private const DAY_OFFSET = 86400;
    /**
     * @param Schedule[]|null $Organizations
     */
    public function __construct()
    {

        $host = '127.0.0.1';
        $db   = 'screator';
        $user = 'root';
        $pass = 'root';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $pdo = new \PDO($dsn, $user, $pass);
        $res = $pdo->query('SELECT * FROM organizations INNER JOIN schedule ON organizations.id = schedule.organization_id');
        $schedule = [];
        while ($row = $res->Fetch()) {
            $schedule[] = $row;
        }

        $this->schedule = $schedule;
        $this->currentTime = date('H:i');
        $this->currentWeek = (int) date('W');
    }


    /**
     * возвращает массив открытых в момент вызова организаций, и время их закрытия
     * @return array
     */
    public function opened(): array
    {
        $res = [];
        $currentTime = strtotime($this->currentTime) + self::HOURS_OFFSET;

        foreach ($this->schedule as $item) {
            $start = strtotime($item['open']);
            $end = strtotime($item['close']);

            if ($end < $start) {
                $end += self::DAY_OFFSET;
            }
            if ($currentTime >= $start && $currentTime <= $end && $item['day_of_week'] == $this->currentWeek) {
                $res[] = [
                    'name' => $item['name'],
                    'timeToClose' => date('H:i', $end - $currentTime) . " hours:minutes",
                ];
            }
        }

        return array_values($res);
    }


    /**
     * Возвращает массив закрытых в момент вызова организаций, и время до их открытия
     * @return array
     */
    public function closed(): array
    {

        $res = [];
        $currentTime = strtotime($this->currentTime) + self::HOURS_OFFSET;

        foreach ($this->schedule as $item) {
            $start = strtotime($item['open']);
            $end = strtotime($item['close']);

            if ($end < $start) {
                $end += self::DAY_OFFSET;
            }
            if ($currentTime < $start || $currentTime > $end || $this->currentWeek != $item['day_of_week']) {
                if ($item['day_of_week'] > $this->currentWeek) {
                    $weekDiff = $item['day_of_week'] - $this->currentWeek;
                } else {
                    $weekDiff = ( 7 - $this->currentWeek ) + $item['day_of_week'];
                }
                $res[] = [

                    'name' => $item['name'],
                    'timeToOpen' => $weekDiff . ' days ' . date('H:i', ($start - $currentTime)) . ' hours:minutes',
                ];
            }
        }
        return array_values($res);
    }
}
