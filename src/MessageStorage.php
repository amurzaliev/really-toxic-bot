<?php
declare(strict_types=1);

namespace App;

use App\Dto\Message;

final class MessageStorage
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function latestMessage(Message $newMessage): ?Message
    {
        $latestMessages = $this->parseLatestMessages();

        $filteredMessages = array_filter($latestMessages, function (Message $message) use ($newMessage) {
            return $newMessage->chatId() === $message->chatId() &&
                $newMessage->messageId() === $message->messageId();
        });

        return count($filteredMessages) ? array_pop($filteredMessages) : null;
    }

    public function saveMessage(Message $message): void
    {
        $latestMessages = $this->parseLatestMessages();

        foreach ($latestMessages as $index => $latestMessage) {
            if ($latestMessage->chatId() === $message->chatId()) {
                unset($latestMessages[$index]);
            }
        }

        $latestMessages[] = $message;

        file_put_contents(
            $this->filename,
            json_encode(['latestMessages' => $latestMessages],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /** @return Message[] */
    private function parseLatestMessages(): array
    {
        $content = file_exists($this->filename) ? file_get_contents($this->filename) : null;

        if (!$content) {
            return [];
        }

        $latestMessages = [];
        $latestMessagesData = json_decode($content, true)['latestMessages'] ?? [];

        foreach ($latestMessagesData as $messageData) {
            $latestMessages[] = Message::fromArray($messageData);
        }

        return $latestMessages;
    }
}