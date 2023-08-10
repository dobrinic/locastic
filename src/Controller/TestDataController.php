<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class TestDataController extends AbstractController
{
    #[Route('/generate/csv', name: 'generate_csv')]
    public function generateCsv(Request $request, string $projectDir): Response
    {
        $length = $request->query->get('length');

        if (is_null($length) || $length < 1 || $length > 1000000){
            $this->addFlash('warning', 'Requested file doesn\'t make sense!');
            return $this->redirectToRoute('app_home');
        }

        $filename = $projectDir . '/testing-data/testing-dataset-'. $length.'.csv';

        if (file_exists($filename)){
            return $this->file(new File($filename));
        }

        $faker = Factory::create();
        $file = fopen($filename, 'w');
        fputcsv($file, ['fullName','distance','time','ageCategory']);

        for ($i = 1; $i <= $length; $i++) {
            $line[] =  $faker->name;
            $line[] = $faker->randomElement(['medium', 'long']);
            $line[] = $faker->time($format = 'H:i:s');
            $line[] = $faker->randomElement(['M18-25', 'M26-34', 'M35-43', 'F18-25', 'F26-34', 'F35-43']);
            fwrite($file, implode(',', $line). PHP_EOL);
            $line = null;
        }
        fclose($file);

        return $this->file(new File($filename));
    }
}
