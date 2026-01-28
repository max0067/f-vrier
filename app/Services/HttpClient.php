<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Client HTTP simple pour les appels API
 */
class HttpClient
{
    private int $timeout = 30;
    private array $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * Effectue une requête GET
     */
    public function get(string $url, array $params = [], array $headers = []): string
    {
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $this->request('GET', $url, null, $headers);
    }

    /**
     * Effectue une requête POST
     */
    public function post(string $url, array $data = [], array $headers = []): string
    {
        return $this->request('POST', $url, $data, $headers);
    }

    /**
     * Effectue une requête HTTP
     */
    private function request(string $method, string $url, ?array $data, array $headers): string
    {
        $headers = array_merge($this->defaultHeaders, $headers);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => $this->formatHeaders($headers),
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("HTTP Request failed: {$error}");
        }

        if ($httpCode >= 400) {
            throw new \RuntimeException("HTTP Error {$httpCode}: {$response}");
        }

        return $response ?: '';
    }

    /**
     * Formate les headers pour cURL
     */
    private function formatHeaders(array $headers): array
    {
        $formatted = [];
        foreach ($headers as $key => $value) {
            $formatted[] = "{$key}: {$value}";
        }
        return $formatted;
    }

    /**
     * Définit le timeout
     */
    public function setTimeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }
}
