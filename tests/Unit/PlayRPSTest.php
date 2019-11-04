<?php

namespace Tests\Unit;

use App\Game;
use DateTime;
use Illuminate\Support\Facades\Storage;
use SoapBox\Formatter\Formatter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayRPSTest extends TestCase
{
    private $game;
    private $outputDirectoryName;
    private $outputFilename;

    public function setUp(): void
    {
        parent::setUp();
        $today = rand(0,5000);
        $this->game = new Game('rps');
        $this->outputFilename = 'game_' . $today . '.csv';
        $this->outputDirectoryName = 'test_game_reports';
    }

    public function test_handle()
    {
        $this->artisan('play:rps')
            ->expectsQuestion('Which game do you want to play?', 'rps')
            ->expectsQuestion($this->game->getJson()["Info"] . ' Do you wish to continue?', 'Yes')
            ->expectsOutput('Let\'s play to rps');
    }

    /**
     * @return string
     */
    public function test_create_csv()
    {
        $results = [];
        $results['win'] = 20;
        $results['draw'] = 10;
        $results['lose'] = 15;
        $results['total'] = 45;

        $formatter = Formatter::make($results, Formatter::ARR);
        $file = $formatter->toCsv("\r\n", ',');

        $this->assertContains('win', $file);
        $this->assertContains('draw', $file);
        $this->assertContains('lose', $file);
        $this->assertContains('total', $file);

        $folderExists = Storage::disk('local')->exists($this->outputDirectoryName);
        if (!$folderExists)
            Storage::makeDirectory($this->outputDirectoryName);
        $path = $this->outputDirectoryName . '/' . $this->outputFilename;
        Storage::disk('local')->put($path, $file);

        $file = storage_path() . '/app/' . $path;
        return $file;
    }

    /**
     * @param $file
     * @depends test_create_csv
     */
    public function test_save_file($file)
    {
        $this->assertFileExists($file);
        unlink($file);
    }
}
