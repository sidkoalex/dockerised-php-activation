<?php

require __DIR__.'/../../activation/ActivationFactory.php';

$cipher = ActivationFactory::cipher();
$generator = ActivationFactory::cipherKeyGenerator();

$key = $generator->generateKey();
$publicKey = $key->getPublicKey();
$privateKey = $key->getPrivateKey();
$data = 'Hello world. It\'s a working cipher example for activation package.';

$encrypted = $cipher->encrypt($data, $publicKey);
$decrypted = $cipher->decrypt($encrypted, $privateKey);

echo "<h1>RSA cipher testing</h1>\n";
echo "<h2>Public key:</h2><textarea rows='10' cols='50'>{$publicKey}</textarea><br/>\n";
echo "<h2>Private key:</h2><textarea rows='15' cols='100'>{$privateKey}</textarea><br/>\n";

echo "<h3>Plain text:</h3><code>{$data}</code><br/>\n";
echo "<h4>Encrypted text:</h4><code>{$encrypted}</code><br/>\n";
echo "<h4>Decrypted text:</h4><code>{$decrypted}</code><br />\n";


