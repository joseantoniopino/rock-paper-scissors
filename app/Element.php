<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    private $name;
    private $strongVS;
    private $weakVS;

    public function __construct($name, $strongVS, $weakVS)
    {
        $this->name = $name;
        $this->strongVS = $strongVS;
        $this->weakVS = $weakVS;

        parent::__construct();
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
}
