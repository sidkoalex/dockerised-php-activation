<?php

require_once __DIR__.'/../mapper/UserSerialMapping.php';

class UserSerial
{
    use UserSerialMapping;

    private $id;

    private $userId;

    private $userName;

    private $serialId;

    private $pcHash;

    private $productName;

    private $status;

    public function __construct($id, $userId, string $userName, int $serialId, string $pcHash, string $productName, string $status)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->serialId = $serialId;
        $this->pcHash = $pcHash;
        $this->productName = $productName;
        $this->status = $status;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getSerialId(): int
    {
        return $this->serialId;
    }

    public function getPcHash(): string
    {
        return $this->pcHash;
    }


    public function getProductName(): string
    {
        return $this->productName;
    }


    public function getStatus(): string
    {
        return $this->status;
    }

    public function setProductName(string $productName)
    {
        $this->productName = $productName;
    }

    public function setPcHash(string $pcHash)
    {
        $this->pcHash = $pcHash;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }
}