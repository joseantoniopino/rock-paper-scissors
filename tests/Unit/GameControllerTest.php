<?php

namespace Tests\Unit;

use App\Game;
use App\Http\Controllers\GameController;
use DirectoryIterator;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameControllerTest extends TestCase
{
    private $gc;
    private $pathToGames;
    private $win;
    private $draw;
    private $lose;
    private $total;
    private $game;

    public function setUp(): void
    {
        parent::setUp();
        $this->gc = new GameController();
        $this->pathToGames = storage_path() . '/games';
        $this->game = new Game('rps');
        $this->win = 26;
        $this->draw = 15;
        $this->lose = 18;
        $this->total = (
            $this->win +
            $this->draw +
            $this->lose
        );
    }

    public function test_determine_result_win()
    {
        $rules["RulesFake"]["ResultFake"]["strongFake"] = ["result2"];
        $strongVS = $rules["RulesFake"]["ResultFake"]["strongFake"];
        $resultPlayerOne = "result1";
        $resultPlayerTwo = "result2";

        if (in_array($resultPlayerTwo, $strongVS)) {
            $roll = 'win';
        } elseif ($resultPlayerOne == $resultPlayerTwo) {
            $roll = 'draw';
        } else {
            $roll = 'lose';
        }
        $this->assertEquals($roll, 'win');
    }

    public function test_determine_result_draw()
    {
        $rules["ResultFake"]["strongFake"] = ["resultFake"];
        $strongVS = $rules["ResultFake"]["strongFake"];
        $resultPlayerOne = "result2";
        $resultPlayerTwo = "result2";

        if (in_array($resultPlayerTwo,$strongVS)){
            $roll = 'win';
        } elseif ($resultPlayerOne == $resultPlayerTwo) {
            $roll = 'draw';
        } else {
            $roll = 'lose';
        }
        $this->assertEquals($roll, 'draw');
    }

    public function test_determine_result_lose()
    {
        $rules["ResultFake"]["strongFake"] = ["resultFake"];
        $strongVS = $rules["ResultFake"]["strongFake"];
        $resultPlayerOne = "result1";
        $resultPlayerTwo = "result2";

        if (in_array($resultPlayerTwo,$strongVS)){
            $roll = 'win';
        } elseif ($resultPlayerOne == $resultPlayerTwo) {
            $roll = 'draw';
        } else {
            $roll = 'lose';
        }
        $this->assertEquals($roll, 'lose');
    }

    public function test_create_results_array()
    {
        $results = [];
        $results['total'] = $this->total;
        $results['win'] = $this->win;
        $results['draw'] = $this->draw;
        $results['lose'] = $this->lose;

        $this->assertIsArray($results);
        $this->assertCount(4,$results);
        $this->assertEquals($results['win'],$this->win);
        $this->assertEquals($results['draw'],$this->draw);
        $this->assertEquals($results['lose'],$this->lose);
        $this->assertEquals($results['total'],$this->total);
        $this->assertArrayHasKey('win',$results);
        $this->assertArrayHasKey('draw',$results);
        $this->assertArrayHasKey('lose',$results);
        $this->assertArrayHasKey('total',$results);
    }

    public function test_list_available_games()
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
        $this->assertIsArray($gamesAvailable);
        $this->assertNotEmpty($gamesAvailable);
        $this->assertCount(2,$gamesAvailable);
        $this->assertContains('rps',$gamesAvailable);
        $this->assertContains('rpsls',$gamesAvailable);
    }

    public function test_play()
    {
        $win = 0;
        $draw = 0;
        $lose = 0;
        $results = [];
        $rules = $this->game->getJson()["Rules"];

        for ($i=0;$i<100;$i++)
        {
            $randomElement = $this->game->randomizeElement();
            $this->game->setElement($randomElement);
            $element = $this->game->getElement();
            $result = $this->gc->determineResult($rules,$element);
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
        $results['lose'] = $lose;

        $total = $results['win'] + $results['draw'] + $results['lose'];

        $this->assertIsArray($results);
        $this->assertCount(3,$results);
        $this->assertEquals(100, $total);
        $this->assertIsInt($results['win']);
        $this->assertIsInt($results['draw']);
        $this->assertIsInt($results['lose']);
    }

    public function test_path_to_games()
    {
        $this->assertEquals(storage_path() . '/games', $this->gc->getPathToGames());
    }
}
