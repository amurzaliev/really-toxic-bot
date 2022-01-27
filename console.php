<?php
declare(strict_types=1);
assert_options(ASSERT_ACTIVE, 1);

use App\BotClient;
use App\ConsoleApp;
use App\Dto\ConsoleCommand;
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

$logger = new Logger('console_app');
$logger->pushHandler(new StreamHandler('log/console_app.log', Logger::DEBUG));

$name = $_SERVER['argv'][1] ?? '';
$argument = $_SERVER['argv'][2] ?? '';
$consoleCommand = new ConsoleCommand($name, $argument);

(new ConsoleApp($client, $settings, $logger))->run($consoleCommand);