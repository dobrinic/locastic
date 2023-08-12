<?php

namespace App\Service;


class AverageRaceTimeService
{
    public function calculate(array $runnersArray): array
    {
        $averageRaceTimes = [
            'medium' => [],
            'long' => []
        ];

        foreach ($runnersArray as $runner) {
            $averageRaceTimes[$runner['distance']][] = strtotime($runner['time']) - strtotime('TODAY');
        }

        $averageTimes = [
            'medium' => $this->calculateAverageTime($averageRaceTimes['medium']),
            'long' => $this->calculateAverageTime($averageRaceTimes['long'])
        ];

        return $averageTimes;
    }

    private function calculateAverageTime(array $times): string
    {
        if (empty($times)) {
            return '00:00:00';
        }

        $countRecords = count($times);
        $totalTime = array_reduce($times, function ($carry, $time) {
            return $carry + $time;
        }, 0);

        $averageSeconds = $totalTime / $countRecords;

        return $this->formatTime(floor($averageSeconds));
    }

    private function formatTime($seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}