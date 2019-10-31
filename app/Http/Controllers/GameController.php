<?php

namespace App\Http\Controllers;

use App\Game;
use DirectoryIterator;
use Exception;

class GameController extends Controller
{
    private $resultPlayerTwo;
    private $pathToGames;

    /**
     * GameController constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->resultPlayerTwo = 'Rock';
        $this->pathToGames = storage_path() . '/games';
    }

    /**
     * @param $rules
     * @param $resultPlayerOne
     * @return string
     */
    public function determineResult($rules,$resultPlayerOne): string
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

    /**
     * @param $win
     * @param $draw
     * @param $lose
     * @return array
     */
    public function createResultsArray($win, $draw, $lose): array
    {
        $results = [];
        $results['total'] = $win + $draw + $lose;
        $results['win'] = $win;
        $results['draw'] = $draw;
        $results['lose'] = $lose;

        return $results;
    }

    /**
     * @return array
     */
    public function listAvailableGames(): array
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

    /**
     * @param Game $game
     * @return array
     */
    public function play(Game $game): array
    {
        $win = 0;
        $draw = 0;
        $lose = 0;
        $results = [];
        $rules = $game->getJson()["Rules"];

        for ($i=0;$i<100;$i++)
        {
            $randomElement = $game->randomizeElement();
            $game->setElement($randomElement);
            $element = $game->getElement();
            $result = $this->determineResult($rules,$element);
            switch ($result) {
                case 'win':
                    $win++;
                    break;
                case 'draw':
                    $draw++;
                    break;
                case 'lose':
                    $lose++;
                    break;
            }
            unset($randomElement,$element,$result);
        }
        $results['win'] = $win;
        $results['draw'] = $draw;
        $results['loose'] = $lose;

        return $results;
    }

    /**
     * @return string
     */
    public function getPathToGames(): string
    {
        return $this->pathToGames;
    }
}
