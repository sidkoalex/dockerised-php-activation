<?php

require_once __DIR__.'/CipherKey.php';

interface CipherKeyGenerator
{
    public function generateKey(): CipherKey;
}
