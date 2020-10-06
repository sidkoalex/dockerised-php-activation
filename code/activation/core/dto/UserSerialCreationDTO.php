<?php

class UserSerialCreationDTO {
    private $userName;
    private $userId;
    private $pcCount;
    private $period;

    public function __construct($userName = null, $userId = null, $pcCount = null, $period = 30)
    {
        $this->userName = $userName;
        $this->userId = $userId;
        $this->pcCount = $pcCount;
        $this->period = $period;
    }

    public function getUserName()
    {
        return $this->userName;
    }


    public function getUserId()
    {
        return $this->userId;
    }

    public function getPcCount()
    {
        return $this->pcCount;
    }

    public function getPeriod()
    {
        return $this->period;
    }
}