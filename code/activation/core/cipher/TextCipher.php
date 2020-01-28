<?php

interface TextCipher
{
    function encrypt(string $text, string $key): string;

    function decrypt(string $base64encryptedText, string $key): string;
}