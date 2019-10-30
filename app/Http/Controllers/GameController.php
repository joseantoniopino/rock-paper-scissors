<?php

namespace App\Http\Controllers;

use DateTime;
use DirectoryIterator;
use Exception;
use Illuminate\Support\Facades\Storage;
use SoapBox\Formatter\Formatter;

class GameController extends Controller
{
    private $resultPlayerTwo;
    private $outputDirectoryName;
    private $outputFilename;
    private $pathToGames;

    /**
     * GameController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $today = new DateTime();
        $this->outputFilename = 'game_' . $today->format('Ymd') . '.csv';
        $this->outputDirectoryName = 'game_reports';
        $this->resultPlayerTwo = 'Rock';
        $this->pathToGames = storage_path() . '/games';
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
        $folderExists = Storage::disk('local')->exists($this->outputDirectoryName);
        $path = $this->outputDirectoryName . '/' . $this->outputFilename;

        if (!$folderExists)
            Storage::makeDirectory($this->outputDirectoryName);

        Storage::disk('local')->put($path, $file);
    }

    public function listAvailableGames()
    {
        $gamesAvailable = [];
        $dir = new DirectoryIterator($this->pathToGames);
        foreach ($dir as $file)
        {
            if ((!$file->isDot()) && ($file->getExtension() === 'json')) {
                $game = str_replace('.json','',$file->getFilename());
                $gamesAvailable[] = $game;
            }
        }
        return $gamesAvailable;
    }
}
