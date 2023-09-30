<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
require_once './vendor/autoload.php';
require './TwilioSMSSender.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

use Twilio\Rest\Client;

// Find your Account SID and Auth Token at twilio.com/console
// and set the environment variables. See http://twil.io/secure

$twilio = new TwilioSMSSender($_ENV['SID'], $_ENV['TOKEN'], $_ENV['MessagingServiceSid']);

$now = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));

$twilio->send(
    '+33600000000',
    'This is a test message',
    $now,
);
