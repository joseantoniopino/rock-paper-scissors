<?php

namespace App\Exceptions;

use Exception;

class NoGamesFoundException extends Exception
{
    public function __construct($pathToGames)
    {
        parent::__construct(sprintf('No files found in %s', $pathToGames));
    }
}
