<?php

require_once __DIR__.'/../mapper/KeyMapping.php';

class Key
{
    use KeyMapping;

    private $id;

    private $publicKey;

    private $privateKey;

    public function __construct($id, string $publicKey, string $privateKey)
    {
        $this->id = $id;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}