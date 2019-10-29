<?php

namespace App\Http\Controllers;

class GameController extends Controller
{
    private $name;
    private $rules;
    private $resultPlayerTwo = 'Rock';

    /**
     * GameController constructor.
     * @param $name
     * @param $rules
     */
    public function __construct($rules, $name)
    {
        $this->name = $name;
        $this->rules = $rules;
    }

    /**
     * @return string
     */
    public function determineResult()
    {
        $rules = $this->rules;
        $resultPlayerOne = $this->name;
        $strongVS = $rules[$resultPlayerOne]['strongVS'];

        if (($strongVS == $this->resultPlayerTwo)){
            $roll = 'win';
        } elseif ($resultPlayerOne == $this->resultPlayerTwo) {
            $roll = 'draft';
        } else {
            $roll = 'lose';
        }
        return $roll;
    }
}
