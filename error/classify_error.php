<?php

function classifyError(string $message, int $httpCode = 0): array
{
    $message = mb_strtolower($message);
    
    // Błędy autoryzacji
    if (str_contains($message, 'authentication') || 
        str_contains($message, 'authorization') ||
        str_contains($message, 'token') ||
        str_contains($message, 'uwierzytelni') ||
        str_contains($message, 'autoryzac') ||
        $httpCode === 401 || $httpCode === 403) {
        return [
            'errorType' => 'user_error',
            'errorCode' => 'AUTH_FAILED',
            'title' => 'Błąd autoryzacji',
            'suggestions' => [
                'Sprawdź czy token KSeF jest poprawny i aktualny',
                'Sprawdź czy NIP jest zgodny z tokenem',
                'Upewnij się że środowisko (DEMO/TEST) pasuje do tokena',
                'Token mógł wygasnąć - wygeneruj nowy w panelu KSeF'
            ]
        ];
    }
    
    // Błędy NIP
    if (str_contains($message, 'nip') || 
        str_contains($message, 'identifier') ||
        str_contains($message, 'subject')) {
        return [
            'errorType' => 'user_error',
            'errorCode' => 'INVALID_NIP',
            'title' => 'Błąd NIP',
            'suggestions' => [
                'Sprawdź czy NIP ma dokładnie 10 cyfr',
                'Sprawdź czy NIP jest zgodny z tokenem KSeF',
                'Upewnij się że firma jest zarejestrowana w KSeF'
            ]
        ];
    }
    
    // Błędy połączenia
    if (str_contains($message, 'curl') || 
        str_contains($message, 'connection') ||
        str_contains($message, 'timeout') ||
        str_contains($message, 'połącz') ||
        $httpCode === 0 || $httpCode === 500 || $httpCode === 502 || $httpCode === 503) {
        return [
            'errorType' => 'server_error',
            'errorCode' => 'KSEF_UNAVAILABLE',
            'title' => 'Serwer KSeF niedostępny',
            'suggestions' => [
                'To nie jest błąd Twoich danych',
                'Serwer KSeF może być przeciążony lub w trakcie konserwacji',
                'Spróbuj ponownie za kilka minut'
            ]
        ];
    }
    
    // Brak faktur
    if (str_contains($message, 'brak faktur') || 
        str_contains($message, 'no invoice') ||
        str_contains($message, 'empty')) {
        return [
            'errorType' => 'info',
            'errorCode' => 'NO_INVOICES',
            'title' => 'Brak faktur',
            'suggestions' => [
                'Nie znaleziono faktur w wybranym okresie',
                'Sprawdź czy zakres dat jest poprawny',
                'Sprawdź czy wybrałeś właściwy typ podmiotu (Sprzedawca/Nabywca)'
            ]
        ];
    }
    
    // Błędy certyfikatu/konfiguracji
    if (str_contains($message, 'certyfikat') || 
        str_contains($message, 'certificate') ||
        str_contains($message, 'public_key') ||
        str_contains($message, 'pem')) {
        return [
            'errorType' => 'app_error',
            'errorCode' => 'CONFIG_ERROR',
            'title' => 'Błąd konfiguracji aplikacji',
            'suggestions' => [
                'Brak lub niepoprawny certyfikat KSeF',
                'Skontaktuj się z administratorem aplikacji'
            ]
        ];
    }
    
    // Domyślny błąd
    return [
        'errorType' => 'unknown_error',
        'errorCode' => 'UNKNOWN',
        'title' => 'Wystąpił błąd',
        'suggestions' => [
            'Spróbuj ponownie',
            'Jeśli problem się powtarza, skontaktuj się z administratorem'
        ]
    ];
}


