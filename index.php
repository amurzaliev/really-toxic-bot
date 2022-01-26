<?php
declare(strict_types=1);
assert_options(ASSERT_ACTIVE, 1);

use App\App;
use App\BotClient;
use App\MessageGenerator;
use App\MessageStorage;
use App\Settings;
use Symfony\Component\Dotenv\Dotenv;

require_once './vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->usePutenv(true);
$dotenv->load(__DIR__ . '/.env.local');
$settings = new Settings(
    getenv('API_URL'),
    getenv('BOT_TOKEN'),
    (int) getenv('RND'),
    (int) getenv('EXPIRE')
);

$client = new BotClient(getenv('API_URL') . getenv('BOT_TOKEN') . '/');
$messageStorage = new MessageStorage(__DIR__ . '/message_storage.json');
$messageGenerator = new MessageGenerator();

(new App($client, $messageStorage, $messageGenerator, $settings))->run();