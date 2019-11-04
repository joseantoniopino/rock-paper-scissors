<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Game;
use Illuminate\Foundation\Testing\WithFaker;

class GameTest extends TestCase
{
    use WithFaker;

    private $game;
    private $rps;
    private $rpsls;


    public function setUp(): void
    {
        parent::setUp();
        $this->rpsls = 'rpsls';
        $this->rps  = 'rps';
        $this->game = new Game($this->rps);
    }

    public function test_path_to_json()
    {
        $testPath = storage_path() . '/games/' . strtolower($this->rps) . '.json';
        $pathToJson = $this->game->getPathToJson();
        $this->assertEquals($testPath,$pathToJson);
    }

    public function test_get_json()
    {
        $testJson = json_decode(file_get_contents(storage_path() . '/games/' . strtolower($this->rps) . '.json'),true);
        $json = $this->game->getJson();
        $this->assertIsArray($json);
        $this->assertEquals($testJson,$json);
    }

    public function test_randomize_element()
    {
        $element = $this->game->randomizeElement();
        $this->assertArrayHasKey($element,$this->game->getJson()['Rules']);
    }

    public function test_keys_in_array()
    {
        $this->assertArrayHasKey('Rules',$this->game->getJson());
        $this->assertArrayHasKey('Info',$this->game->getJson());

        $game = new Game('rpsls');
        $this->assertArrayHasKey('Rules',$game->getJson());
        $this->assertArrayHasKey('Info',$game->getJson());
    }

    public function test_get_set_element()
    {
        $element = $this->faker->lexify('element_?????');
        $this->game->setElement($element);
        $getElement = $this->game->getElement();
        $this->assertEquals($element,$getElement);
    }
}
