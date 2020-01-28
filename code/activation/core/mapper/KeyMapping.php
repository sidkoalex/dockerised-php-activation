<?php

require_once __DIR__.'/../entity/Key.php';
require_once __DIR__.'/../cipher/CipherKey.php';

trait KeyMapping
{
    public static function fromDbRow(array $row)
    {
        return new Key($row['id'], $row['public_key'], $row['private_key']);
    }

    public static function fromCipherKey(CipherKey $cipherKey)
    {
        return new Key(null, $cipherKey->getPublicKey(), $cipherKey->getPrivateKey());
    }

    public function toDbRow()
    {
        return [
            'id' => $this->getId(),
            'private_key' => $this->getPrivateKey(),
            'public_key' => $this->getPublicKey(),
        ];
    }

    abstract public function getPrivateKey(): string;

    abstract public function getPublicKey(): string;
}