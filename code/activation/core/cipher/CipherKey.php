<?php

class CipherKey
{
    private $privateKey;
    private $publicKey;

    public function __construct(string $privateKey, string $publicKey)
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }


    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }


    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
