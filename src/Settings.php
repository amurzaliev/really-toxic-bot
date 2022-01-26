<?php
declare(strict_types=1);

namespace App;

final class Settings
{
    private string $apiUrl;
    private string $botToken;
    private int $rnd;
    private int $expire;

    public function __construct(string $apiUrl, string $botToken, int $rnd, int $expire)
    {
        $this->apiUrl = $apiUrl;
        $this->botToken = $botToken;
        $this->rnd = $rnd;
        $this->expire = $expire;
    }

    public function apiUrl(): string
    {
        return $this->apiUrl;
    }

    public function botToken(): string
    {
        return $this->botToken;
    }

    public function rnd(): int
    {
        return $this->rnd;
    }

    public function expire(): int
    {
        return $this->expire;
    }
}