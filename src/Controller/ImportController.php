<?php

namespace App\Controller;

use App\Service\CsvReaderAdapter;
use App\Service\PlacementsCalculatorService;
use App\Service\RacePersisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/import', name: 'import_')]
class ImportController extends AbstractController
{
    #[Route('/csv', name: 'csv')]
    public function index(Request $request, CsvReaderAdapter $readerAdapter, PlacementsCalculatorService $placementsCalculator, RacePersisterService $persister): Response
    {
        $csvFile = $request->files->get('csvFile');
        $title = $request->request->get('raceTitle');
        $date = $request->request->get('raceDate');

        if (!$csvFile || !$title || !$date) {
            return $this->json(['message' => 'All fields in the form are mandatory!'], 400);
        }

        $runnersArray = $readerAdapter->parseFile($csvFile);
        $sortedRunnersArray = $placementsCalculator->calculatePlacements($runnersArray);
        $success = $persister->saveRunners($title, $date, $sortedRunnersArray);

        return $this->json(['success' => $success, 'message' => $success ? 'Succesfully imported CSV to the database.' : 'There was a problem saving your data to database.']);
    }
}
