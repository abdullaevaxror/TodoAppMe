<?php

use App\Bot;
use App\Todo;

$bot = new Bot();
$todo = new Todo();

$update = json_decode(file_get_contents('php://input'));
$chatId = $update->message->chat->id;
$text = $update->message->text;
//$bot->makeRequest('sendMessage', [
//    'chat_id' => 6177186948,
//    'text' => json_encode($update, JSON_PRETTY_PRINT),
//]);
if ($text == '/start') {
    $bot->makeRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => 'Welcome to the Todo App'
    ]);
    exit();
}
// "/start" -> "/start"
if (mb_stripos($text, '/start') !== false) {
    $userId = explode('/start', $text)[1];
    $taskList = "";
    $bot->makeRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => 'Welcome to the Todo App (mb_stripos) ' . $userId
    ]);
    $todo->setTelegramId((int)$userId, $chatId);
    $bot->makeRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => 'telegram id add'
    ]);
    exit();
}
//if (mb_stripos($text, '/start') !== false) {
//    // Foydalanuvchi ID ni olish
//    $userId = trim(explode('/start', $text)[1] ?? '');
//
//    // Foydalanuvchi ID ni tekshirish (bo'sh yoki noto'g'ri formatda emasligini tasdiqlash)
//    if (!ctype_digit($userId)) {
//        $bot->makeRequest('sendMessage', [
//            'chat_id' => $chatId,
//            'text' => 'Invalid user ID format. Please check the input.'
//        ]);
//        exit();
//    }
//
//    // Xush kelibsiz xabarini yuborish
//    $bot->makeRequest('sendMessage', [
//        'chat_id' => $chatId,
//        'text' => 'Welcome to the Todo App! User ID: ' . $userId
//    ]);
//
//    // Telegram ID ni yangilash
//    try {
//        $user->setTelegramId((int)$userId, $chatId); // ID ni int turiga o'tkazish
//        $bot->makeRequest('sendMessage', [
//            'chat_id' => $chatId,
//            'text' => 'Telegram ID successfully added.'
//        ]);
//    } catch (Exception $e) {
//        $bot->makeRequest('sendMessage', [
//            'chat_id' => $chatId,
//            'text' => 'An error occurred while updating the Telegram ID: ' . $e->getMessage()
//        ]);
//    }
//
//    exit();
//}

if ($text == '/help') {
    $bot->makeRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => "Show task -> /tasks \n"
    ]);
    exit();
}
if ($text === '/tasks') {
    try {
        $tasks = $todo->getTasksByChatId($chatId);

        if (empty($tasks)) {
            $bot->makeRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => 'No tasks found!'
            ]);
        } else {
            $taskList = "Your Tasks:\n";
            $buttons = [];
            $i = 1;
            foreach ($tasks as $task) {
                $taskList .= "$i - " . $task['title'] . " (" . $task['status'] . ") - Due: " . $task['due_date'] . "\n";
                $buttons[] = ['text' => $i, 'callback_data' => 'task_' . $task['id']];
                $i++;
            }
            $buttons =array_chunk($buttons, 3);
            $keyboard = ['inline_keyboard' => $buttons];

            $bot->makeRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => $taskList,
                'reply_markup' => json_encode($keyboard)
            ]);
        }
    } catch (Exception $e) {
        $bot->makeRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => 'An error occurred while fetching your tasks: ' . $e->getMessage()
        ]);
    }
}
if (isset($update->callback_query)) {
    $callbackQuery = $update->callback_query;
    $callbackData = $callbackQuery->data;
    $chatId = $callbackQuery->message->chat->id;
    $messageId = $callbackQuery->message->message_id;

    if (mb_stripos($callbackData, 'task_') === 0) {
        $taskId = (int) str_replace('task_', '', $callbackData);

        $task = $todo->getTaskById($taskId);

        if ($task) {
            $buttons = [
                [
                    ['text' => 'In Progress', 'callback_data' => 'in_progress_' . $taskId],
                    ['text' => 'Completed', 'callback_data' => 'completed_' . $taskId],
                    ['text' => 'Pending', 'callback_data' => 'pending_' . $taskId],
                ],
                [
                    ['text' => 'Cancel', 'callback_data' => 'cancel_task']
                ]
            ];

            $keyboard = ['inline_keyboard' => $buttons];
            $bot->makeRequest('editMessageText', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => "Task: " . $task['title'] . "\nCurrent Status: " . $task['status'],
                'reply_markup' => json_encode($keyboard)
            ]);
        } else {
            $bot->makeRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "Task not found."
            ]);
        }
    } elseif (mb_stripos($callbackData, 'in_progress_') === 0 || mb_stripos($callbackData, 'completed_') === 0 || mb_stripos($callbackData, 'pending_') === 0) {
        $dataParts = explode('_', $callbackData);
        $newStatus = $dataParts[0];
        $taskId = (int) $dataParts[1];

        $success = $todo->updateTaskStatus($taskId, $newStatus);

        if ($success) {
            $bot->makeRequest('editMessageText', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => "Task #{$taskId} updated to '{$newStatus}'."
            ]);

            sleep(3);
            $bot->makeRequest('deleteMessage', [
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]);
            $bot->makeRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "Task '{$taskId}' successfully updated to '{$newStatus}'."
            ]);
        } else {
            $bot->makeRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "Failed to update the task status."
            ]);
        }
    } elseif ($callbackData === 'cancel_task') {
        $bot->makeRequest('deleteMessage', [
            'chat_id' => $chatId,
            'message_id' => $messageId
        ]);

        // Tasklar ro‘yxatini qayta ko‘rsatish
        try {
            $tasks = $todo->getTasksByChatId($chatId);

            if (empty($tasks)) {
                $bot->makeRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => 'No tasks found!'
                ]);
            } else {
                $taskList = "Your Tasks:\n";
                $buttons = [];
                $i = 1;
                foreach ($tasks as $task) {
                    $taskList .= "$i - " . $task['title'] . " (" . $task['status'] . ") - Due: " . $task['due_date'] . "\n";
                    $buttons[] = ['text' => $i, 'callback_data' => 'task_' . $task['id']];
                    $i++;
                }
                $buttons =array_chunk($buttons, 3);
                $keyboard = ['inline_keyboard' => $buttons];

                $bot->makeRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => $taskList,
                    'reply_markup' => json_encode($keyboard)
                ]);
            }
        } catch (Exception $e) {
            $bot->makeRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => 'An error occurred while fetching your tasks: ' . $e->getMessage()
            ]);
        }
    }
}

