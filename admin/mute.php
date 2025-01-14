<?php


function minutesToSeconds($minutes) {
    return $minutes * 60;
}

function hoursToSeconds($hours) {
    return $hours * 3600;
}

function daysToSeconds($days) {
    return $days * 86400;
}

function yearsToSeconds($years) {
    return $years * 31536000;
}

function restrictUser($chat_id, $user_id, $mute_duration) {
    $permissions = [
        'can_send_messages' => false
    ];

    $until_date = time();
    
    if (strpos($mute_duration, 'm') !== false) {
        $minutes = (int)filter_var($mute_duration, FILTER_SANITIZE_NUMBER_INT);
        $until_date += minutesToSeconds($minutes);
    } elseif (strpos($mute_duration, 'h') !== false) {
        $hours = (int)filter_var($mute_duration, FILTER_SANITIZE_NUMBER_INT);
        $until_date += hoursToSeconds($hours);
    } elseif (strpos($mute_duration, 'd') !== false) {
        $days = (int)filter_var($mute_duration, FILTER_SANITIZE_NUMBER_INT);
        $until_date += daysToSeconds($days);
    } elseif (strpos($mute_duration, 'y') !== false) {
        $years = (int)filter_var($mute_duration, FILTER_SANITIZE_NUMBER_INT);
        $until_date += yearsToSeconds($years);
    } else {
        // Si no se especificó una unidad de tiempo válida, asumimos que es en segundos.
        $seconds = (int)filter_var($mute_duration, FILTER_SANITIZE_NUMBER_INT);
        $until_date += $seconds;
    }

    $params = [
        'chat_id' => $chat_id,
        'user_id' => $user_id,
        'permissions' => $permissions,
        'until_date' => $until_date
    ];

    return sendRequest('restrictChatMember', $params);
}


$chat_id = YOUR_CHAT_ID;
$user_id = USER_TO_MUTE_ID;
$mute_duration = '1h30m'; // Silenciar por 1 hora y 30 minutos

$result = restrictUser($chat_id, $user_id, $mute_duration);