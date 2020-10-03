<?php
session_start();

require_once __DIR__.'/../../activation/ActivationService.php';

$shouldAddNewSerial = isset($_POST['action']) && $_POST['action'] == 'new_serial';
$shouldActivateSerial = isset($_POST['action']) && $_POST['action'] == 'activate_serial';

if ($shouldAddNewSerial) {
    $userId = null;
    $userName = $_POST['name'];
    $pcCount = $_POST['pc_count'];

    $dto = new SerialCreationDTO($userName, null, $pcCount);
    ActivationService::instance()->makeNewSerialForUser($dto);
}

if ($shouldActivateSerial) {
    $serial = $_POST['serial'];
    $pcHash = $_POST['pc_hash'];

    $dto = new SerialActivationInputDTO($serial, $pcHash);
    $activationResult = ActivationService::instance()->activateSerial($dto);
}
?>
<style>
    table {
        word-break: break-all;
        max-width: 1200px;
        background: #f6f6f6;
    }

    tr:nth-child(even) {
        background: #e3eae8
    }

    td {
        padding: 0.5em;
    }

    table, td, th {
        border-color: #ccc;
    }

    pre {
        display: block;
        max-width: 800px;
        overflow: auto;
    }

    .response-details {
        font: 0.8em Monospaced;
        color: #444;
        max-width: 800px;
    }
</style>

<h2>Create new serial for user</h2>
<form action="licensemanager.php" method="post">
    <input type="hidden" name="action" value="new_serial"/>
    <table border="1">
        <tr>
            <td>Name:</td>
            <td><input type="text" name="name" value="John Tao"></td>
        </tr>
        <tr>
            <td>PC count:</td>
            <td><label><input type="radio" name="pc_count" value="1" checked>1</label>
                <label><input type="radio" name="pc_count" value="3">3</label>
                <label><input type="radio" name="pc_count" value="5">5</label></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Create new serial" name="submit"></td>
        </tr>
    </table>
</form>

<h2>Activate user serial</h2>
<form action="licensemanager.php" method="post">
    <input type="hidden" name="action" value="activate_serial"/>
    <table border="1">
        <tr>
            <td>Serial:</td>
            <td><input type="text" name="serial" value=""></td>
        </tr>
        <tr>
            <td>PC hash:</td>
            <td><input type="text" name="pc_hash" value="12345"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Activate!" name="submit"></td>
        </tr>
    </table>
</form>

<?php if (isset($activationResult)) : ?>
    <table class="response-details">
        <tr>
            <td colspan="2">Details:</td>
        </tr>
        <?php foreach ($activationResult as $key => $value): ?>
            <tr>
                <td width="15%"><?php echo $key ?></td>
                <td><?php echo $value; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>


<h2>User serial table</h2>
<?php $userSerials = ActivationFactory::userSerialRepository()->findAll(); ?>
<?php $statusColors = [
    SerialStatusEnum::NOT_USED => "#bbb",
    SerialStatusEnum::ACTIVATED => "#393",
]; ?>
<table border='1'>
    <tr>
        <th>id</th>
        <th>user_id</th>
        <th>user_name</th>
        <th>serial_id</th>
        <th>pc_hash</th>
        <th>status</th>
    </tr>
    <?php $prevSerialId = 0; ?>
    <?php foreach ($userSerials as $userSerial): ?>
        <tr>
            <td> <?php echo $userSerial->getId(); ?></td>
            <td> <?php echo $userSerial->getUserId(); ?></td>
            <td style="color: <?php echo '#'.substr(md5($userSerial->getSerialId()), 0, 6); ?>">
                <?php echo $userSerial->getUserName(); ?>
            </td>
            <td> <?php echo $userSerial->getSerialId(); ?></td>
            <td> <?php echo $userSerial->getPcHash(); ?></td>
            <td style="color: <?php echo $statusColors[$userSerial->getStatus()] ?>">
                <?php echo $userSerial->getStatus(); ?>
            </td>
        </tr>
        <?php $prevSerialId = $userSerial->getSerialId(); ?>
    <?php endforeach; ?>
</table>

<h2>Serial table</h2>
<?php $serials = ActivationFactory::serialRepository()->findAll(); ?>
<table border='1'>
    <tr>
        <th>id</th>
        <th>key_id</th>
        <th>is_banned</th>
        <th>serial</th>
        <th>expire_date</th>
    </tr>
    <?php foreach ($serials as $serial): ?>
        <tr>
            <td> <?php echo $serial->getId(); ?></td>
            <td> <?php echo $serial->getKeyId(); ?></td>
            <td> <?php echo $serial->isBanned() ? 'true' : 'false'; ?></td>
            <td style="color: <?php echo '#'.substr(md5($serial->getId()), 0, 6); ?>">
                <?php echo $serial->getSerial(); ?>
            </td>
            <td> <?php echo $serial->getExpireDate(); ?></td>
        </tr>
    <?php endforeach; ?>
</table>


<h2>Key table</h2>
<?php $keys = ActivationFactory::keyRepository()->findAll(); ?>
<table border='1'>
    <tr>
        <th>id</th>
        <th>public_key</th>
        <th>private_key</th>
    </tr>
    <?php foreach ($keys as $key): ?>
        <tr>
            <td> <?php echo $key->getId(); ?></td>
            <td> <?php echo $key->getPublicKey(); ?></td>
            <td> <?php echo $key->getPrivateKey(); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
