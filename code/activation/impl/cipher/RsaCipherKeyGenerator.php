<?php

require_once __DIR__.'/../../core/cipher/CipherKeyGenerator.php';
require_once __DIR__.'/../../core/cipher/CipherKey.php';

class RsaCipherKeyGenerator implements CipherKeyGenerator
{
    private $config = [
        "digest_alg" => "sha512",
        "private_key_bits" => 1024,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];

    public function generateKey(): CipherKey
    {
        // Create the private and public key
        $res = openssl_pkey_new($this->config);

        // Extract the private key from $res to $privKey
        openssl_pkey_export($res, $privKey);

        // Extract the public key from $res to $pubKey
        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];

        return new CipherKey($privKey, $pubKey);
    }
}