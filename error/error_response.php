<?php 

require_once __DIR__ . '/classify_error.php';

function errorResponse(string $message, int $httpCode = 0): array
{
    $classified = classifyError($message, $httpCode);
    
    return [
        'success' => false,
        'errorType' => $classified['errorType'],
        'errorCode' => $classified['errorCode'],
        'title' => $classified['title'],
        'message' => $message,
        'suggestions' => $classified['suggestions']
    ];
}