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
    protected $description = 'Execute this command to start the rock, paper, scissors game (rps) or rock, paper, scissors, wizard, spock (rpsws)';

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
     * @return mixed
     */
    public function handle()
    {
        $element = new Game('rps');
        $name = $element->getName();
        $strongVS = $element->getStrongVS();
        $rules = $element->getJson();

        $result = new GameController($rules,$name);
        dd($name, $strongVS, $result->determineResult());
    }
}
