<?php

namespace App;

class Game
{
    private $element;
    private $strongVS;
    private $weakVS;
    private $info;
    private $pathToJson;
    private $json;


    /**
     * Element constructor.
     * @param $game
     */
    public function __construct($game)
    {
        $this->pathToJson = storage_path() . '/games/' . strtolower($game) . '.json';
        $this->json = json_decode(file_get_contents($this->pathToJson),true);
        $this->element = $this->randomizeElement();
        $this->strongVS = $this->json["Rules"][$this->element]['strongVS'];
        $this->weakVS = $this->json["Rules"][$this->element]['weakVS'];
        $this->info = $this->json['Info'];
    }



    /**
     * @return mixed
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param mixed $element
     */
    public function setElement($element)
    {
        $this->element = $element;
    }

    /**
     * @return mixed
     */
    public function getStrongVS()
    {
        return $this->strongVS;
    }

    /**
     * @param mixed $strongVS
     */
    public function setStrongVS($strongVS): void
    {
        $this->strongVS = $strongVS;
    }

    /**
     * @return mixed
     */
    public function getWeakVS()
    {
        return $this->weakVS;
    }

    /**
     * @param mixed $weakVS
     */
    public function setWeakVS($weakVS): void
    {
        $this->weakVS = $weakVS;
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param mixed $json
     */
    public function setJson($json): void
    {
        $this->json = $json;
    }

    /**
     * @return mixed
     */
    public function randomizeElement()
    {
        $element = array_rand($this->json["Rules"],1);
        return $element;
    }
}
