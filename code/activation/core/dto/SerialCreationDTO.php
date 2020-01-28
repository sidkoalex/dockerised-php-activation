<?php

class SerialCreationDTO {
    private $userName;
    private $userId;
    private $pcCount;

    public function __construct($userName = null, $userId = null, $pcCount = null)
    {
        $this->userName = $userName;
        $this->userId = $userId;
        $this->pcCount = $pcCount;
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
}