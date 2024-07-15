<?php

declare(strict_types=1);

require 'vendor/autoload.php';
require 'DB.php';
// require 'Currency.php';

use GuzzleHttp\Client;

$token = '6038247949:AAEAkQNMwsFUynBu-wxOhPEaGgSrbDELp0w';
$tgApi = "https://api.telegram.org/bot$token/";

$client = new Client(['base_uri' => $tgApi]);

$update = json_decode(file_get_contents('php://input'));

$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => '🇺🇸 USD => 🇺🇿 UZS', 'callback_data' => 'usd:uzs'],
            ['text' => '🇺🇿 UZS => 🇺🇸 USD', 'callback_data' => 'uzs:usd']
        ]
    ]
];

$db = new DB();
$currency = new Currency();

if (isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $text = $message->text;

    if ($text == '/start') {
        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => 'Welcome to Currency Converter Bot. Please choose conversion type:',
                'reply_markup' => json_encode($keyboard)
            ]
        ]);
    }

    if (is_numeric($text)) {
        $result = $db->sendStateName($chat_id, (float)$text);
        $state = $db->sendStateNameUser($chat_id);

        $db->allUsersInfo($chat_id, $state, (int)$result);

        $client->post('sendMessage', [
            'form_params' => [
                'chat_id' => $chat_id,
                'text' => $result,
            ]
        ]);
    }
}

if (isset($update->callback_query)) {
    $callbackQuery = $update->callback_query;
    $chat_id = $callbackQuery->message->chat->id;
    $callback_data = $callbackQuery->data;

    $db->stateName($chat_id, $callback_data);

    $client->post('sendMessage', [
        'form_params' => [
            'chat_id' => $chat_id,
            'text' => 'Qiymat kiriting',
        ]
    ]);
}
