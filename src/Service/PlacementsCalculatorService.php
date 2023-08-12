<?php

namespace App\Service;


class PlacementsCalculatorService
{
    public function calculatePlacements(array $runnersArray): array
    {
        usort($runnersArray, function ($a, $b) {
            $timeA = strtotime($a['time']);
            $timeB = strtotime($b['time']);
            return $timeA - $timeB;
        });

        $placement = 1;
        $groupedResults = [];

        foreach ($runnersArray as $runner) {
            $ageCategory = $runner['ageCategory'];

            if (!isset($groupedResults[$ageCategory])) {
                $groupedResults[$ageCategory] = [];
            }

            $runner['placement'] = $runner['distance'] === 'long' ? $placement++ : null;
            $groupedResults[$ageCategory][] = $runner;
        }

        unset($runnersArray);

        $sortedResults = [];
        
        foreach ($groupedResults as $ageCategory => $results) {
            usort($results, function ($a, $b) {
                $timeA = strtotime($a['time']);
                $timeB = strtotime($b['time']);
                return $timeA - $timeB;
            });
            
            $ageCategoryPlacement = 1;

            foreach ($results as $runner) {
                $runner['ageCategoryPlacement'] = $runner['distance'] === 'long' ? $ageCategoryPlacement++ : null;
                $sortedResults[] = $runner;
            }
        }

        unset($groupedResults);

        return $sortedResults;
    }
}