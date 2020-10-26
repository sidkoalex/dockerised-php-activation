<?php
header('Content-Type: application/json');

require_once __DIR__.'/../activation/ActivationService.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data)) $data = json_decode(base64_decode(file_get_contents('php://input')));

if (isset($data['serial']) && isset($data['pc_hash']) && isset($data['product_name'])) {
    $serial = $data['serial'];
    $pcHash = $data['pc_hash'];
    $productName = $data['product_name'];

    $dto = new SerialActivationInputDTO($serial, $pcHash, $productName);
    $activationResult = ActivationService::instance()->activateSerial($dto);

    echo json_encode($activationResult);
} else {
    http_response_code(500);
    echo json_encode(["error"=>"Incorrect or missing data: 'serial' and 'pc_hash' parameters were expected", "error_name" => "INVALID_PARAMS"]);
}

