<?php

require_once __DIR__ . '/ActivationFactory.php';
require_once __DIR__ . '/impl/util/uuid.php';
require_once __DIR__ . '/core/dto/SerialActivationInputDTO.php';
require_once __DIR__ . '/core/dto/SerialActivationOutputDTO.php';
require_once __DIR__ . '/core/dto/UserSerialCreationDTO.php';
require_once __DIR__ . '/core/dto/SerialCreationDTO.php';

class ActivationService
{
    private static $instance = null;

    public static function instance(): ActivationService
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Create and save new serial number for user.
     * If serial number or key for this serial number has not created yet, they will be created.
     *
     * @param \UserSerialCreationDTO $creationDTO
     * @return void New user serial record id
     * @see \ActivationService::makeNewKey()
     */
    public function makeNewSerialForUser(UserSerialCreationDTO $creationDTO)
    {
        $newSerialCreationDTO = new SerialCreationDTO($creationDTO->getPeriod());
        $newSerialId = $this->makeNewSerial($newSerialCreationDTO);

        $newUserSerial = new UserSerial(
            null,
            $creationDTO->getUserId(),
            $creationDTO->getUserName(),
            $newSerialId,
            "",
            "",
            SerialStatusEnum::NOT_USED,
            null);

        for ($i = 0; $i < min([$creationDTO->getPcCount(), 100]); $i++)
            ActivationFactory::userSerialRepository()->save($newUserSerial);
    }

    /**
     * Create and save new serial number using last generated key.
     * If key has not generated yet, "makeNewKey()" will be called.
     * If last key was used more than 5 times, new key will be created.
     *
     * @param SerialCreationDTO $creationDTO serial creation settings
     * @return int New serial id
     * @throws
     * @see \ActivationService::makeNewKey()
     */
    public function makeNewSerial(SerialCreationDTO $creationDTO): int
    {

        $lastKey = ActivationFactory::keyRepository()->findLast();
        $lastKeyId = is_null($lastKey) ? null : $lastKey->getId();
        $lastKeyIdUseCount = is_null($lastKeyId) ? -1 : ActivationFactory::serialRepository()->countKeyUse($lastKeyId);
        $lastKeyId = $lastKeyIdUseCount >= 5 ? $this->makeNewKey() : $lastKeyId;
        $lastKeyId = is_null($lastKeyId) ? $this->makeNewKey() : $lastKeyId;

        $newSerialNumber = uuid();
        $newSerialPeriod = $creationDTO->getPeriod();

        $newSerial = new Serial(null, $lastKeyId, false, $newSerialNumber, $newSerialPeriod);
        $id = ActivationFactory::serialRepository()->save($newSerial);

        return $id;
    }

    /**
     * Generate and save new key pair using CipherKeyGenerator
     *
     * @return int New key id
     * @throws
     */
    public function makeNewKey(): int
    {
        $cipherKey = ActivationFactory::cipherKeyGenerator()->generateKey();
        $key = Key::fromCipherKey($cipherKey);
        $id = ActivationFactory::keyRepository()->save($key);

        return $id;
    }

    public function activateSerial(SerialActivationInputDTO $activationDTO): SerialActivationOutputDTO
    {
        if (empty($activationDTO->getSerial()) || empty($activationDTO->getPcHash())) {
            return SerialActivationOutputDTO::ofStatus(SerialActivationStatusEnum::NOT_FOUND);
        }


        // Found Serial
        $serial = ActivationFactory::serialRepository()->findBySerialNo($activationDTO->getSerial());
        if ($serial == null) {
            return SerialActivationOutputDTO::ofStatus(SerialActivationStatusEnum::NOT_FOUND);
        }


        // Check User Serial count
        $notUsedCount = ActivationFactory::userSerialRepository()->countNotUsed($serial->getId());
        if ($notUsedCount <= 0) {
            return SerialActivationOutputDTO::ofStatus(SerialActivationStatusEnum::INTERNAL_ACTIVATION_LIMIT_EXCEED);
        }


        // Found User Serial
        $userSerial = ActivationFactory::userSerialRepository()->findBySerialIdAndStatus($serial->getId(), SerialStatusEnum::NOT_USED);
        if ($userSerial == null) {
            return SerialActivationOutputDTO::ofStatus(SerialActivationStatusEnum::NOT_FOUND);
        }

        if ($serial->isBanned()) {
            return SerialActivationOutputDTO::ofStatus(SerialActivationStatusEnum::IS_BANNED);
        }

        // Update user serial as ACTIVATED
        $userSerial->setPcHash($activationDTO->getPcHash());
        $userSerial->setProductName($activationDTO->getProductName());
        $userSerial->setStatus(SerialStatusEnum::ACTIVATED);
        $userSerial->setActivatedAt(date('Y-m-d'));
        $userSerial->setExpiry(date('Y-m-d', strtotime('+'.$serial->getPeriod().' days')));
        ActivationFactory::userSerialRepository()->save($userSerial);

        // Find encrypted hash
        $keyId = $serial->getKeyId();
        $key = ActivationFactory::keyRepository()->findById($keyId);
        $dataToSignArray = array(
            $activationDTO->getPcHash(),
            $userSerial->getProductName(),
            $userSerial->getActivatedAt(),
            $serial->getPeriod());
        $dataToSignStr = join(",", $dataToSignArray);
        $dataToSignHash = sha1($dataToSignStr);
        $signedDataBase64 = ActivationFactory::cipher()->sign($dataToSignHash, $key->getPrivateKey());

        // Create result
        $result = new SerialActivationOutputDTO(
            SerialActivationStatusEnum::ACTIVATED,
            $userSerial->getUserName(),
            $serial->getSerial(),
            $signedDataBase64,
            $key->getPublicKey(),
            $serial->getPeriod(),
            $userSerial->getActivatedAt(),
            $userSerial->getProductName(),
            $activationDTO->getPcHash()
        );

        return $result;
    }
}