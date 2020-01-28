<?php

require_once __DIR__.'/../../core/cipher/TextCipher.php';

class RsaTextCipher implements TextCipher
{
    public function encrypt(string $text, string $key): string
    {
        openssl_public_encrypt($text, $encryptedData, $key);

        return base64_encode($encryptedData);
    }

    public function decrypt(string $base64encryptedText, string $key): string
    {
        $encryptedText = base64_decode($base64encryptedText);
        openssl_private_decrypt($encryptedText, $decryptedText, $key);

        return $decryptedText;
    }
}