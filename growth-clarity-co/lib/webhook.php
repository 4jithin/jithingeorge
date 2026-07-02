<?php
/**
 * Best-effort POST to a Zapier (or any) webhook. Failures are swallowed —
 * the webhook is a secondary write (Google Sheets mirror); MySQL is the
 * system of record and must never be blocked by it.
 */
function gcc_fire_webhook(string $url, array $payload): void
{
    if ($url === '') return;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 4,
        CURLOPT_CONNECTTIMEOUT => 3,
    ]);
    curl_exec($ch);
    curl_close($ch);
}
