<?php
declare(strict_types=1);

namespace App;

use App\Dto\Message;

final class MessageGenerator
{
    private array $statements = [
        'Согласен',
        'Лучше сгоняй за пивом',
        'Пёс',
        'Это ты про меня?',
        'Очень интересно, продолжай',
        'Видимо, зубная фея была сегодня очень довольна тобой, поросёночек!',
        'Господи, спасибо тебе за идиотов!',
        'Да, мы поняли, НАМ НАСРАТЬ!'
    ];

    private array $questions = [
        'Да',
        'Нет',
        'Если ты пидор - то да',
        'Не отвечайте на это!'
    ];

    public function generate(Message $message): string
    {
        if (mb_stripos($message->text(), '?') !== false) {
            return $this->questions[array_rand($this->questions)];
        }

        return $this->statements[array_rand($this->statements)];
    }
}
