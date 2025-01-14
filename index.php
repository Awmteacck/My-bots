<?php

ini_set('log_errors', TRUE);
ini_set('error_log', 'errors.log');

require_once 'recursos/function.php';

date_default_timezone_set('America/Lima');

define('BOT_TOKEN', '7740237222:AAEUJJBK7iLOMYTsox9epp7iG2unPQvYXmY');
define('TELEGRAM_API_URL', 'https://api.telegram.org/bot' . 7740237222:AAEUJJBK7iLOMYTsox9epp7iG2unPQvYXmY);


// Manejo de mensajes de entrada
$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);
$print = print_r($update);
$chat_id = $update["message"]["chat"]["id"];
$chat_type = $update["message"]["chat"]["type"];
$user_id = $update["message"]["from"]["id"];
$firstname = $update["message"]["from"]["first_name"];
$username = $update["message"]["from"]["username"];
$message = $update["message"]["text"];
$message_id = $update["message"]["message_id"];

$photo = $update["message"]["photo"]["file_id"];
$documento = $update["message"]["document"];

$r_message = $update['message']['reply_to_message']['text'];

$data = $update['callback_query']['data'];
$callback_chat_id = $update["callback_query"]["message"]["chat"]["id"];
$callback_message_id = $update["callback_query"]["message"]["message_id"];

$callback_from = $update['callback_query']['from']['id'];
$callback_user_id = $update['callback_query']['message']['reply_to_message']['from']['id'];
$callback_id = $update['callback_query']['id'];
$callback_bin = $update['callback_query']['message']['reply_to_message']['text'];
$callback_firstname = $update['callback_query']['from']['first_name'];
$callback_lastname = $update['callback_query']['from']['last_name'];




$owner = "<a href='tg://user?id=1425540240'> Gato [”9“8”9“6]</a>";



$off = 'OFF ”9è2';
$on = 'ON •0®8';

// Enviar una solicitud a la API de Telegram
function sendRequest($method, $params = [])
{
    $url = TELEGRAM_API_URL . '/' . $method;
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($params),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false,$context);
    return json_decode($result, true);
}

// Enviar un mensaje de texto
function sendMessage($chat_id, $message, $message_id = null)
{
    $params = [
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML',
        'reply_to_message_id' => $message_id,
    ];
    return sendRequest('sendMessage', $params);
}

// Enviar una foto
function sendPhoto($chat_id, $foto, $caption = null, $message_id = null)
{
    $params = [
        'chat_id' => $chat_id,
        'photo' => $foto,
        'caption' => $caption,
        'parse_mode' => 'HTML',
        'reply_to_message_id' => $message_id,
    ];
    return sendRequest('sendPhoto', $params);
}

// Enviar un video
function sendVideo($chat_id, $video, $caption = null, $keyboard = null, $message_id = null)
{
    $params = [
        'chat_id' => $chat_id,
        'video' => $video,
        'caption' => $caption,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard,
        'reply_to_message_id' => $message_id,
    ];
    return sendRequest('sendVideo', $params);
}

//Enviar documento
function sendDocument($chat_id, $documentFilePath, $message = null)
{
    $params = [
        'chat_id' => $chat_id,
        'document' => new CURLFile(realpath($documentFilePath)),
        'caption' => $message,
    ];

    return sendRequest('sendDocument', $params);
}


// Editar un mensaje
function editMessage($chat_id, $message, $message_id)
{
    $params = [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'text' => $message,
        'parse_mode' => 'HTML',
    ];
    return sendRequest('editMessageText', $params);
}

// Eliminar un mensaje
function deleteMessage($chat_id, $message_id)
{
    $params = [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
    ];
    return sendRequest('deleteMessage', $params);
}

// Enviar acci¨®n de chat (typing, uploading_photo, recording_video, etc.)
function sendChatAction($chat_id, $action)
{
    $params = [
        'chat_id' => $chat_id,
        'action' => $action,
    ];
    return sendRequest('sendChatAction', $params);
}

// Obtener fotos de perfil de usuario
function getUserProfilePhotos($user_id)
{
    $params = [
        'user_id' => $user_id,
    ];
    return sendRequest('getUserProfilePhotos', $params);
}

// Enviar botones en chat
function reply_to($chat_id, $message_id = null, $keyboard, $message)
{
    $params = [
        'chat_id' => $chat_id,
        'text' => $message,
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard,
    ];
    return sendRequest('sendMessage', $params);
}

function answerCallbackQuery($callback_id, $message)
{
    $params = [
        'callback_query_id' => $callback_id,
        'text' => $message,
        'show_alert' => true
    ];

    return sendRequest('answerCallbackQuery', $params);
}

function editMessageCaption($chat_id, $message_id, $caption, $reply_markup)
{
    $params = [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'caption' => $caption,
        'reply_markup' => $reply_markup,
    ];

    return sendRequest('editMessageCaption', $params);
}



$proxie = 'http://p.webshare.io:80';
$pass = 'kzdeknxk-rotate:n1yai0b7ldc6';

#$proxie = null;
#$pass = null;

$passOptions = [
    'rvpoydac-rotate:um0wzobkke09',
    'zwvccpuz-rotate:0ggj0crja35e',
    'yzdiiyzb-rotate:wgrrjdqfwmin',
    'kzdeknxk-rotate:n1yai0b7ldc6',
];

$pass = $passOptions[array_rand($passOptions)];


include_once 'tools/boton.php';

// L¨®gica del bot

$words = explode(' ', $message, 2);
$firstWord = $words[0];

if (ltrim($message, ".,/") === "start") {
    sendChatAction($chat_id, 'typing');
    sendMessage($chat_id, "hello world", $message_id);
} elseif (ltrim($firstWord, '.,/') === 'st') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/st.php';
} elseif (strpos($message, '/cmds') === 0) {
    require_once 'tools/cmds.php';
} elseif (ltrim($firstWord, '.,/') === 'au') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/au.php';
} elseif (ltrim($firstWord, '.,/') === 'pi') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/pi.php';
} elseif (ltrim($firstWord, '.,/') === 'ch') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/ch.php';
} elseif (ltrim($firstWord, '.,/') === 'bb') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/bb.php';
} elseif (ltrim($firstWord, '.,/') === 'va') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/va.php';
} elseif (ltrim($firstWord, '.,/') === 'mn') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/mn.php';
} elseif (ltrim($firstWord, '.,/') === 'bl') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/bl.php';
} elseif (ltrim($firstWord, '.,/') === 'pz') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/pz.php';
} elseif (ltrim($firstWord, '.,/') === 'pp') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/pp.php';
} elseif (ltrim($firstWord, '.,/') === 'sp') {
    sendChatAction($chat_id, 'typing');
    require_once 'gate/sp.php';
} elseif (ltrim($firstWord, '.,/') === 'bin') {
    require_once 'tools/bin.php';
} elseif (ltrim($firstWord, '.,/') === 'gen') {
    require_once 'tools/gen.php';
} elseif (ltrim($firstWord, '.,/') === 'fake') {
    require_once 'tools/fake.php';
} elseif (ltrim($firstWord, '.,/') === 'ip') {
    require_once 'tools/ip.php';
} elseif (ltrim($firstWord, '.,/') === 'key') {
    require_once 'tools/key.php';
} elseif (ltrim($firstWord, '.,/') === 'register') {
    require_once 'tools/r.php';
} elseif (ltrim($message, ".,/") === "info") {
    require_once 'tools/info.php';
}elseif (ltrim($firstWord, '.,/') === 'claim') {
    require_once 'tools/claim.php';
} elseif (strpos($message, '/add') === 0) {
    require_once 'admin/gate.php';
} elseif (strpos($message, '/act') === 0) {
    require_once 'admin/actgate.php';
} elseif (strpos($message, '/gro') === 0) {
    require_once 'admin/group.php';
} elseif (strpos($message, '/update') === 0) {
    require_once 'admin/plans.php';
} elseif (strpos($message, '/sms') === 0) {
    require_once 'admin/sms.php';
} elseif (strpos($message, '/copia') === 0) {
    require_once 'admin/copia.php';
} elseif (strpos($message, '/warn') === 0) {
    require_once 'admin/warns.php';
} elseif (ltrim($firstWord, '.,/') === 'tik') {
    require_once 'othertools/tik.php';
} 
