<?php

namespace App\Console\Commands;

use App\Game;
use App\Http\Controllers\GameController;
use Illuminate\Console\Command;

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param GameController $gameController
     * @return mixed
     */
    public function handle(GameController $gameController): void
    {
        $gamesAvailable = $gameController->listAvailableGames();
        $gameName = $this->choice('Which game do you want to play?',$gamesAvailable,$gamesAvailable[1]);
        $game = new Game($gameName);
        $info = $game->getJson()["Info"];
        $continue = $this->confirm($info . ' Do you wish to continue?');

        if ($continue){
            $win = 0;
            $draw = 0;
            $lose = 0;

            $rules = $game->getJson()["Rules"];

            for ($i=0;$i<100;$i++)
            {
                $randomElement = $game->randomizeElement();
                $game->setElement($randomElement);
                $element = $game->getElement();
                $result = $gameController->determineResult($rules,$element);
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

            if ($win > $lose){
                $message = 'You are the champion!';
            } elseif ($lose > $win){
                $message = 'You are a looser :P';
            } else {
                $message = 'Draw!';
            }

            $results = $gameController->createResultsArray($win,$draw,$lose);

            $gameController->createCSV($results);

            $this->table(['Total', 'Win', 'Draw', 'Lose'],[$results]);
            $this->line($message);
        } else {
            $this->warn('Exit!');
        }
    }
}
