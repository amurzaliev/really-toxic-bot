<?php
declare(strict_types=1);

namespace App;

use App\Dto\Message;

final class MessageGenerator
{
    private array $statements = [
        'Согласен',
        'Сгоняй за пивом, пёс',
        'Не может быть'
    ];

    private array $questions = [
        'Да',
        'Нет',
        'Если ты пидор - то да',
    ];

    public function generate(Message $message): string
    {
        if (mb_stripos($message->text(), '?') !== false) {
            return $this->statements[array_rand($this->statements)];
        }

        return $this->questions[array_rand($this->questions)];
    }
}