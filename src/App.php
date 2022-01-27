<?php
declare(strict_types=1);

namespace App;

use App\Dto\Message;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

final class App
{
    private BotClient $client;
    private MessageGenerator $messageGenerator;
    private Settings $settings;
    private LoggerInterface $logger;

    public function __construct(
        BotClient        $client,
        MessageGenerator $messageGenerator,
        Settings         $settings,
        LoggerInterface  $logger
    )
    {
        $this->client = $client;
        $this->messageGenerator = $messageGenerator;
        $this->settings = $settings;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $paths = explode('/', $_SERVER['REQUEST_URI']);
        $method = $paths[1] ?? null;
        $token = $paths[2] ?? null;

        if ($token !== $this->settings->botToken()) {
            http_response_code(403);

            return;
        }

        switch ($method) {
            case 'sendUpdates':
                $this->reply();
                break;
        }

        http_response_code(200);
    }

    private function reply(): void
    {
        $rnd = rand(1, 1000);

        if ($rnd > $this->settings->rnd()) {
            return;
        }

        $response = json_decode(file_get_contents('php://input'), true);

        $this->logger->debug('=== RESPONSE ===', $response);

        if (empty($response)) {
            return;
        }

        $message = new Message(
            (int)$response['update_id'],
            (int)$response['message']['message_id'],
            (int)$response['message']['chat']['id'],
            $response['message']['from']['username'],
            $response['message']['text'],
            DateTimeImmutable::createFromFormat('U', (string)$response['message']['date']),
        );

        if ($this->settings->expire() < $message->date()->diff(new DateTimeImmutable())->i) {
            return;
        }

        $this->client->sendMessage(
            $message->messageId(),
            $message->chatId(),
            $this->messageGenerator->generate($message)
        );
    }
}