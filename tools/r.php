<?php

if($user_id != $chat_id){
  sendMessage($chat_id,'Error: registration must be in private chat',$message_id);
  exit();
}

$db = conectarBaseDatos();

// Verificar si el usuario ya está registrado
$sql = "SELECT * FROM usuarios WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$registroExistente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($registroExistente) {
    sendMessage($chat_id, "<b>⚠ | User Is Already Registered In The Database.</b>", $message_id);
    exit();
} else {
    // Continuar con la inserción de los datos

    $rango = 'free user';
    $creditos = 0;
    $anti_spam = 30;
    $plan = 'basico';
    $warns = 0;
    $registro = date('Y-m-d');
    $plan_expire = '2024-12-01';
    $last_message_time = date('Y-m-d H:i:s');

    $sql = "INSERT INTO usuarios (user_id, rango, creditos, anti_spam, plan, warns, registro, chat_id, plan_expire, last_message_time)
            VALUES (:user_id, :rango, :creditos, :anti_spam, :plan, :warns, :registro, :chat_id, :plan_expire, :last_message_time)";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rango', $rango);
    $stmt->bindParam(':creditos', $creditos);
    $stmt->bindParam(':anti_spam', $anti_spam);
    $stmt->bindParam(':plan', $plan);
    $stmt->bindParam(':warns', $warns);
    $stmt->bindParam(':registro', $registro);
    $stmt->bindParam(':chat_id', $chat_id);
    $stmt->bindParam(':plan_expire', $plan_expire);
    $stmt->bindParam(':last_message_time', $last_message_time);

    if ($stmt->execute()) {
        sendMessage($chat_id, "<b>✅ Successful Registration In The database .\n~ Use The /info Command To Get Detailed Information .</b>", $message_id);
        $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?chat_id=5871846598&text=<b>NEW USER\nID: $user_id\nNANE:$firstname\nUSER_NAME: $username</b>";
    file_get_contents($url);
    } else {
        sendMessage($chat_id, "error inserting data: " . $stmt->errorInfo()[2], $message_id);
    }
}
