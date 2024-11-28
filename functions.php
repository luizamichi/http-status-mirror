<?php

/**
 * Valida se o código de status HTTP está no intervalo válido (100-599).
 *
 * @param int $statusCode Código de status HTTP.
 * @return bool Indica se o código é válido.
 */
function isValidStatusCode(int $statusCode): bool
{
    return $statusCode >= 100 && $statusCode <= 599;
}

/**
 * Aplica um tempo de atraso na resposta, se especificado.
 *
 * @param float $timeDelay Tempo de atraso em segundos.
 * @param float $elapsedTime Tempo já gasto.
 * @return void
 */
function applyDelay(float $timeDelay, float $elapsedTime = 0): void
{
    if ($timeDelay > $elapsedTime) {
        usleep((int) ($timeDelay * 1e6) - (int) ($elapsedTime * 1e6)); // Converte segundos para microssegundos.
    }
}

/**
 * Valida se um texto é um JSON válido.
 *
 * @param ?string $data Texto a ser validado.
 * @return bool Verificação de JSON válido.
 */
function isValidJson(?string $data): bool
{
    if (empty($data)) {
        return false;
    }

    json_decode($data);
    return json_last_error() === JSON_ERROR_NONE;
}

/**
 * Transforma o conteúdo do body em um formato estético para salvar no log.
 *
 * @param ?string $body Conteúdo do body enviado na requisição.
 * @return string Conteúdo do body minificado.
 */
function formatRequestBody(?string $body): string
{
    $formattedBody = 'null';

    if (isValidJson($body)) {
        $decodedBody = json_decode((string) $body, true);
        $formattedBody = (string) json_encode($decodedBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    return $formattedBody;
}

/**
 * Registra informações detalhadas sobre a requisição no arquivo de log.
 *
 * @param string $logFile Caminho do arquivo de log.
 * @param int $statusCode Código de status HTTP retornado.
 * @param float $timeDelay Tempo de atraso configurado.
 * @param float $uptime Tempo gasto para processamento da requisição.
 * @param string $remoteHost Host remoto que fez a requisição.
 * @param string $requestMethod Método HTTP utilizado na requisição.
 * @param string $contentType Tipo do conteúdo enviado na requisição.
 * @param ?string $body Conteúdo do body enviado na requisição.
 * @return void
 */
function logRequest(string $logFile, int $statusCode, float $timeDelay, float $uptime, string $remoteHost, string $requestMethod, string $contentType, ?string $body = null): void
{
    $logEntry = sprintf(
        '[%s] Host: %s | Method: %s | Status: %d | Delay: %.2fs | Uptime: %.2fs | Type: %s | Body: %s' . PHP_EOL,
        date('Y-m-d H:i:s'),
        $remoteHost,
        $requestMethod,
        $statusCode,
        $timeDelay,
        $uptime,
        $contentType,
        formatRequestBody($body)
    );

    file_put_contents($logFile, $logEntry, FILE_APPEND);
}
