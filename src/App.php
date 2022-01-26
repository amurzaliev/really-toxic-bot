<?php
declare(strict_types=1);

namespace App;

use DateTimeImmutable;

final class App
{
    private BotClient $client;
    private MessageStorage $messageStorage;
    private MessageGenerator $messageGenerator;
    private Settings $settings;

    public function __construct(
        BotClient $client,
        MessageStorage $messageStorage,
        MessageGenerator $messageGenerator,
        Settings $settings
    )
    {
        $this->client = $client;
        $this->messageStorage = $messageStorage;
        $this->messageGenerator = $messageGenerator;
        $this->settings = $settings;
    }

    public function run(): void
    {
        $rnd = rand(1, 1000);

        if ($rnd > $this->settings->rnd()) {
            return;
        }

        $message = $this->client->getLastMessage();

        if ($this->settings->expire() < $message->date()->diff(new DateTimeImmutable())->i) {
            return;
        }

        $latestMessage = $this->messageStorage->latestMessage($message);

        if ($latestMessage && $latestMessage->updateId() === $message->updateId()) {
            return;
        }

        $this->client->sendMessage(
            $message->messageId(),
            $message->chatId(),
            $this->messageGenerator->generate($message)
        );

        $this->messageStorage->saveMessage($message);
    }
}