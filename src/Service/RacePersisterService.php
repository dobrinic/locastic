<?php

namespace App\Service;

use App\Entity\Race;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;

use function Symfony\Component\Clock\now;

class RacePersisterService
{
    private $dbConnection;
    private $entityManager;
    private $logger;

    public function __construct(Connection $dbConnection, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->dbConnection = $dbConnection;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function saveRunners(string $title, string $date, array $runners)
    {
        $averageRaceTimeService = new AverageRaceTimeService();
        $averageRaceTimesArray = $averageRaceTimeService->calculate($runners);

        try{
            $this->dbConnection->beginTransaction();

            $race = new Race();
            $race->setTitle($title);
            $race->setDate(new DateTimeImmutable($date));
            $race->setAverageTimeLong(new DateTimeImmutable($averageRaceTimesArray['long']));
            $race->setAverageTimeMedium(new DateTimeImmutable($averageRaceTimesArray['medium']));
            $this->entityManager->persist($race);
            $this->entityManager->flush();

            $batchSize = 25;
            $batchCount = 0;
            $batchRunnerData = [];
            $raceId = $race->getId();

            foreach ($runners as $data) {

                $now = now()->format('Y-m-d h:m:s');
                $runnerData = [
                    'race_id' => $raceId,
                    'name' => $data['fullName'],
                    'age_category' => $data['ageCategory'],
                    'distance' => $data['distance'],
                    'finish_time' => $data['time'],
                    'placement' => $data['placement'],
                    'age_placement' => $data['ageCategoryPlacement'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];

                $batchRunnerData[] = $runnerData;
                $batchCount++;

                if ($batchCount >= $batchSize) {
                    $this->insertBatchData($batchRunnerData);

                    $batchRunnerData = [];
                    $batchCount = 0;
                }
            }

            if (!empty($batchRunnerData)) {
                $this->insertBatchData($batchRunnerData);
            }

            $this->dbConnection->commit();

        }catch(Exception $e){
            $this->dbConnection->rollBack();
            $this->entityManager->remove($race);
            $this->entityManager->flush();
            $this->logger->error(sprintf('There was an error saving records to the database with exception: %s', $e->getMessage()));
            return false;
        }

        return true;
    }

    private function insertBatchData(array $data): void
    {
        if (empty($data)) {
            return;
        }

        $columns = array_keys($data[0]);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $placeholders = rtrim(str_repeat('('.$placeholders.'), ', count($data)), ', ');

        $sql = "INSERT INTO runners (" . implode(', ', $columns) . ") VALUES $placeholders";

        $values = [];
        foreach ($data as $row) {
            foreach ($row as $value) {
                $values[] = $value;
            }
        }

        $this->dbConnection->executeStatement($sql, $values);
    }
}