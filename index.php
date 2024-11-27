<?php

require_once 'functions.php';

// Configura o endereço do arquivo de log.
$logFile = __DIR__ . '/requests.log';

// Obtém a carimbo de data/hora da requisição.
$requestTime = isset($_SERVER['REQUEST_TIME']) && is_int($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
$requestTime = new DateTimeImmutable('@' . (string) $requestTime);

// Obtém a URI solicitada e o IP do cliente que fez a requisição.
$requestUri = is_string($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/';
$remoteHost = is_string($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';

// Obtém o método HTTP da requisição
$requestMethod = is_string($_SERVER['REQUEST_METHOD']) ? (string) $_SERVER['REQUEST_METHOD'] : '';

// Extrai o status code da URI, se fornecido.
preg_match('/\/(\d+)/', $requestUri, $matches);
$statusCode = isset($matches[1]) ? (int) $matches[1] : 200;

// Checa se o parâmetro 'timeDelay' foi fornecido na query string.
$timeDelay = isset($_GET['timeDelay']) && is_numeric($_GET['timeDelay']) ? (float) $_GET['timeDelay'] : 0;

// Valida se o código de status está no intervalo válido 100-599.
if (!isValidStatusCode($statusCode)) {
    $statusCode = 500; // Resposta padrão para códigos inválidos.
}

// Obtém o body enviado na requisição, se fornecido.
$requestBody = (string) file_get_contents('php://input');

// Registra a requisição no arquivo de log.
logRequest($logFile, $statusCode, $timeDelay, $remoteHost, $requestMethod, $requestBody);

// Calcula o tempo gasto de processamento para descontar no atraso.
$currentTime = new DateTimeImmutable();
$timeDiff = $currentTime->getTimestamp() - $requestTime->getTimestamp();

// Adiciona o tempo de atraso, se fornecido.
applyDelay($timeDelay, $timeDiff);

// Envia o código de status e o body (somente se for um JSON válido).
http_response_code($statusCode);

// Valida se o body é JSON válido.
if (isValidJson($requestBody) && $statusCode !== 204) {
    header('Content-Type: application/json');
    echo $requestBody;
}

exit;
