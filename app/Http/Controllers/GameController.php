<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Illuminate\Support\Facades\Storage;
use SoapBox\Formatter\Formatter;

class GameController extends Controller
{
    private $resultPlayerTwo;
    private $folderName;
    private $outputFilename;

    /**
     * GameController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $today = new DateTime();
        $this->outputFilename = 'game_' . $today->format('Ymd') . '.csv';
        $this->folderName = 'game_reports';
        $this->resultPlayerTwo = 'Rock';
    }

    /**
     * @param $rules
     * @param $resultPlayerOne
     * @return string
     */
    public function determineResult($rules,$resultPlayerOne)
    {
        $strongVS = $rules[$resultPlayerOne]['strongVS'];

        if (in_array($this->resultPlayerTwo,$strongVS)){
            $roll = 'win';
        } elseif ($resultPlayerOne == $this->resultPlayerTwo) {
            $roll = 'draw';
        } else {
            $roll = 'lose';
        }
        return $roll;
    }

    public function createResultsArray($win,$draw,$lose)
    {
        $results = [];
        $results['total'] = $win + $draw + $lose;
        $results['win'] = $win;
        $results['draw'] = $draw;
        $results['lose'] = $lose;

        return $results;
    }

    public function createCSV($results)
    {
        $formatter = Formatter::make($results, Formatter::ARR);
        $file = $formatter->toCsv("\r\n", ',');
        $this->saveFile($file);
    }

    private function saveFile($file)
    {
        $folderExists = Storage::disk('local')->exists($this->folderName);
        $path = $this->folderName . '/' . $this->outputFilename;

        if (!$folderExists)
            Storage::makeDirectory($this->folderName);

        Storage::disk('local')->put($path, $file);
    }
}
