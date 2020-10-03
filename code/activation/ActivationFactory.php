<?php

require_once __DIR__.'/impl/cipher/RsaCipherKeyGenerator.php';
require_once __DIR__.'/impl/cipher/RsaTextCipher.php';
require_once __DIR__.'/impl/repository/MysqlKeyRepository.php';
require_once __DIR__.'/impl/repository/MysqlSerialRepository.php';
require_once __DIR__.'/impl/repository/MysqlUserSerialRepository.php';
require_once __DIR__.'/core/config/DbConfig.php';

class ActivationFactory
{
    static $dbConfig;

    static $services;

    private function __construct()
    {
    }

    public static function cipherKeyGenerator(): CipherKeyGenerator
    {
        return self::getOrCreateInstance('cipherKeyGenerator', RsaCipherKeyGenerator::class, []);
    }

    public static function cipher(): TextCipher
    {
        return self::getOrCreateInstance('cipher', RsaTextCipher::class, []);
    }

    public static function keyRepository(): KeyRepository
    {
        return self::getOrCreateInstance('keyRepository', MysqlKeyRepository::class, [self::$dbConfig]);
    }

    public static function serialRepository(): SerialRepository
    {
        return self::getOrCreateInstance('serialRepository', MysqlSerialRepository::class, [self::$dbConfig]);
    }

    public static function userSerialRepository(): UserSerialRepository
    {
        return self::getOrCreateInstance('userSerialRepository', MysqlUserSerialRepository::class, [self::$dbConfig]);
    }

    private static function getOrCreateInstance($name, string $className, array $constructorArgs)
    {
        if (! isset(self::$services[$name])) {
            self::$services[$name] = new $className(...$constructorArgs);
        }

        return self::$services[$name];
    }
}

if ($_SERVER['HTTP_HOST']=='some.url')
		ActivationFactory::$dbConfig = new DbConfig('localhost', 'root', 'mysql', 'activation');
    else
		ActivationFactory::$dbConfig = new DbConfig('localhost', '', '', '');

ActivationFactory::$services = [];