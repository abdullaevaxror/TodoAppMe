<?php

namespace App;

use Dotenv\Dotenv;
use GuzzleHttp\Client;

class Bot
{
    private $client; // Guzzle klienti
    private $botToken; // Telegram bot tokeni

    public function __construct()
    {
        $this->client = new Client();
        $this->botToken = getenv('TELEGRAM_BOT_TOKEN'); // .env fayldan tokenni oladi
    }

    // Xabar yuborish funksiyasi
    public function sendMessage($chatId, $message)
    {
        try {
            $response = $this->client->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'form_params' => [
                    'chat_id' => $chatId,
                    'text' => $message
                ]
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // Botdan kelgan so'rovni qayta ishlash
    public function handleRequest($request)
    {
        $update = json_decode($request, true);
        if (isset($update['message']['text']) && isset($update['message']['chat']['id'])) {
            $chatId = $update['message']['chat']['id'];
            $text = $update['message']['text'];

            // Foydalanuvchi so'roviga javob berish
            if (strtolower($text) === 'salom') {
                $this->sendMessage($chatId, 'Salom! Qanday yordam bera olaman?');
            } else {
                $this->sendMessage($chatId, "Sizning xabaringiz: $text");
            }
        }
    }
}
