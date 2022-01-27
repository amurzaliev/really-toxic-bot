<?php
declare(strict_types=1);

namespace App;

use App\Dto\Message;
use DateTimeImmutable;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

final class BotClient
{
    private Client $client;

    public function __construct(string $baseUri)
    {
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    public function getLastMessage(): ?Message
    {
        $response = $this->client->request('GET', 'getUpdates', [
            RequestOptions::QUERY => [
                'offset'          => -1,
                'limit'           => 10,
                'timeout'         => 1,
                'allowed_updates' => ['message'],
            ],
        ]);

        $result = json_decode($response->getBody()->getContents(), true)['result'][0] ?? [];

        if (empty($result)) {
            return null;
        }

        return new Message(
            (int)$result['update_id'],
            (int)$result['message']['message_id'],
            (int)$result['message']['chat']['id'],
            $result['message']['from']['username'],
            $result['message']['text'],
            DateTimeImmutable::createFromFormat('U', (string)$result['message']['date']),
        );
    }

    public function sendMessage(int $messageId, int $chatId, string $message): void
    {
        $this->client->request('POST', 'sendMessage', [
            RequestOptions::JSON => [
                'chat_id'             => $chatId,
                'reply_to_message_id' => $messageId,
                'text'                => $message,
            ],
        ]);
    }

    public function setWebhook(string $webhookUrl): void
    {
        $this->client->request('POST', 'setWebhook', [
            RequestOptions::JSON => [
                'url' => $webhookUrl,
            ],
            'debug'              => true,
        ]);
    }
}