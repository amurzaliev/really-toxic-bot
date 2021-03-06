<?php
declare(strict_types=1);
assert_options(ASSERT_ACTIVE, 1);

use App\App;
use App\BotClient;
use App\MessageGenerator;
use App\Settings;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Dotenv\Dotenv;

require_once './vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->usePutenv(true);
$dotenv->load(__DIR__ . '/.env.local');
$settings = new Settings(
    getenv('API_URL'),
    getenv('BOT_TOKEN'),
    (int) getenv('RND'),
    (int) getenv('EXPIRE'),
    getenv('WEBHOOK_URL')
);

$client = new BotClient($settings->apiUrl() . $settings->botToken() . '/');
$messageGenerator = new MessageGenerator();

$logger = new Logger('app');
$logger->pushHandler(new StreamHandler('log/app.log', Logger::DEBUG));

(new App($client, $messageGenerator, $settings, $logger))->run();