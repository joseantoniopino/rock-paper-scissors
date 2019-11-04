<?php

namespace App\Console\Commands;

use App\Exceptions\NoGamesFoundException;
use App\Game;
use App\Http\Controllers\GameController;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use SoapBox\Formatter\Formatter;

class PlayRPS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play:rps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute this command to start playing games like rock, paper, scissors.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $outputDirectoryName;
    private $outputFilename;

    public function __construct()
    {
        $today = new DateTime();
        $this->outputFilename = 'game_' . $today->format('Ymd') . '.csv';
        $this->outputDirectoryName = 'game_reports';
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param GameController $gameController
     * @return mixed
     * @throws NoGamesFoundException
     */
    public function handle(GameController $gameController): void
    {
        $gamesAvailable = $gameController->listAvailableGames();

        if (count($gamesAvailable) == 0)
            throw new NoGamesFoundException($gameController->getPathToGames());

        $game = null;
        $gameName = '';
        $continue = false;
        while (!$continue){
            $gameName = $this->choice('Which game do you want to play?',$gamesAvailable,$gamesAvailable[1]);
            $game = new Game($gameName);
            $info = $game->getJson()["Info"];
            $continue = $this->confirm($info . ' Do you wish to continue?');
        }
        $this->output->section('Let\'s play to ' . $gameName);

        $results = $gameController->play($game);

        $this->createCSV($results);

        $this->table(['Total', 'Win', 'Draw', 'Lose'],[$results]);

        $message = $this->determineOutputMessage($results['win'], $results['lose']);

        $this->output->section($message);
    }

    /**
     * @param $win
     * @param $lose
     * @return string
     */
    private function determineOutputMessage($win, $lose): string
    {
        if ($win > $lose){
            $message = 'You are the champion!';
        } elseif ($lose > $win){
            $message = 'You are a looser :P';
        } else {
            $message = 'Draw!';
        }

        return $message;
    }

    /**
     * @param $results
     * @return void
     */
    private function createCSV($results): void
    {
        $formatter = Formatter::make($results, Formatter::ARR);
        $file = $formatter->toCsv("\r\n", ',');
        $this->saveFile($file);
    }

    /**
     * @param $file
     * @return void
     */
    private function saveFile($file): void
    {
        $folderExists = Storage::disk('local')->exists($this->outputDirectoryName);
        $path = $this->outputDirectoryName . '/' . $this->outputFilename;

        if (!$folderExists)
            Storage::makeDirectory($this->outputDirectoryName);

        Storage::disk('local')->put($path, $file);
    }
}
