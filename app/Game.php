<?php

namespace App;

class Game
{
    private $name;
    private $strongVS;
    private $weakVS;
    private $pathToJson;
    private $json;


    /**
     * Element constructor.
     * @param $game
     */
    public function __construct($game)
    {
        $this->pathToJson = storage_path() . '/' . $game . '.json';
        $this->json = json_decode(file_get_contents($this->pathToJson),true);
        $this->name = array_rand($this->json,1);
        $this->strongVS = $this->json[$this->name]['strongVS'];
        $this->weakVS = $this->json[$this->name]['weakVS'];

    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
}
