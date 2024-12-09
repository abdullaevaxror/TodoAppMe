<?php

require 'vendor/autoload.php';

use App\Bot;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bot = new Bot();
    $bot->handleRequest(file_get_contents('php://input'));
}
echo "Telegram ishladi!";