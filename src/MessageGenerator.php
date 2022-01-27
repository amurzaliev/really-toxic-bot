<?php
declare(strict_types=1);

namespace App;

use App\Dto\Message;

final class MessageGenerator
{
    private array $statements = [
        'Согласен',
        'Сгоняй за пивом, пёс',
        'Не может быть',
        'Видимо, зубная фея была сегодня очень довольна тобой, поросёночек!',
        'Господи, спасибо тебе за идиотов!',
        'Да, мы поняли, НАМ НАСРАТЬ!'
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
