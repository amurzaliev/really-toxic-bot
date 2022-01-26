<?php
declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

final class Message implements JsonSerializable
{
    private int $updateId;
    private int $messageId;
    private int $chatId;
    private string $username;
    private string $text;
    private DateTimeImmutable $date;

    public function __construct(int $updateId, int $messageId, int $chatId, string $username, string $text, DateTimeImmutable $date)
    {
        $this->updateId = $updateId;
        $this->messageId = $messageId;
        $this->chatId = $chatId;
        $this->username = $username;
        $this->text = $text;
        $this->date = $date;
    }

    public function updateId(): int
    {
        return $this->updateId;
    }

    public function messageId(): int
    {
        return $this->messageId;
    }

    public function chatId(): int
    {
        return $this->chatId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public static function fromArray(array $data): self
    {
        assert(isset($data['updateId']) && is_numeric($data['updateId']), 'Invalid data');
        assert(isset($data['messageId']) && is_numeric($data['messageId']), 'Invalid data');
        assert(isset($data['chatId']) && is_numeric($data['chatId']), 'Invalid data');
        assert(isset($data['username']) && is_string($data['username']), 'Invalid data');
        assert(isset($data['text']) && is_string($data['text']), 'Invalid data');
        assert(isset($data['date']) && is_int($data['date']), 'Invalid data');

        return new self($data['updateId'], $data['messageId'], $data['chatId'], $data['username'], $data['text'], DateTimeImmutable::createFromFormat('U', (string) $data['date']));
    }

    #[ArrayShape(['updateId' => "int", 'messageId' => "int", 'chatId' => "int", 'username' => "string", 'text' => "string", 'date' => "\DateTimeImmutable"])]
    public function jsonSerialize(): array
    {
        return [
            'updateId'  => $this->updateId,
            'messageId' => $this->messageId,
            'chatId'    => $this->chatId,
            'username'  => $this->username,
            'text'      => $this->text,
            'date'      => (int) $this->date->format('U'),
        ];
    }
}