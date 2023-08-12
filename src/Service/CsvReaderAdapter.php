<?php

namespace App\Service;

use League\Csv\Reader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvReaderAdapter
{
    private $csvDataArray = [];

    public function parseFile(UploadedFile $file): array
    {
        $pathname = $file->getPathname();
        $csv = Reader::createFromPath($pathname);
        $csv->setHeaderOffset(0);
        
        $this->csvDataArray = iterator_to_array($csv->getRecords());

        return $this->csvDataArray;
    }
}