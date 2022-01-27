<?php
declare(strict_types=1);

namespace App;

use App\Dto\ConsoleCommand;
use Psr\Log\LoggerInterface;

final class ConsoleApp
{
    private BotClient $client;
    private Settings $settings;
    private LoggerInterface $logger;

    public function __construct(
        BotClient $client,
        Settings $settings,
        LoggerInterface $logger
    )
    {
        $this->client = $client;
        $this->settings = $settings;
        $this->logger = $logger;
    }

    public function run(ConsoleCommand $command): void
    {
        switch ($command->name()) {
            case ConsoleCommand::COMMAND_SET_WEBHOOK:
                $this->client->setWebhook($this->settings->webhookUrl());
                $this->logger->info('=== SET WEBHOOK ===', ['webhookUrl' => $this->settings->webhookUrl()]);
                return;
        }
    }
}