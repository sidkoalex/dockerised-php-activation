<?php

class SerialCreationDTO {
    private $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function getPeriod()
    {
        return $this->period;
    }
}