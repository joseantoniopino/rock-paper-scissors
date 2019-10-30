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
    protected $description = 'Execute this command to start the rock, paper, scissors game (rps) or rock, paper, scissors, lizard, spock (rpsls)';

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
        $win = 0;
        $draw = 0;
        $lose = 0;

        for ($i=0;$i<100;$i++)
        {
            $game = new Game('rpsls');
            $name = $game->getName();
            $rules = $game->getJson();
            $result = $gameController->determineResult($rules,$name);

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
            unset($game,$name,$match,$result);
        }

        $results = $gameController->createResultsArray($win,$draw,$lose);

        $gameController->createCSV($results);

        $this->table(['Total', 'Win', 'Draw', 'Lose'],[$results]);
    }
}
