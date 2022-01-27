<?php
declare(strict_types=1);

namespace App\Dto;

final class ConsoleCommand
{
    public const COMMAND_SET_WEBHOOK = 'setWebhook';

    private string $name;
    private string $argument;

    public function __construct(string $name, string $argument)
    {
        $this->name = $name;
        $this->argument = $argument;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function argument(): string
    {
        return $this->argument;
    }
}