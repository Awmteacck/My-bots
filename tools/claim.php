<?php

$key = trim(substr($message, 7));

$db = conectarBaseDatos();

// Consulta para verificar si el user_id existe en la tabla "usuarios"
$sql_verify = "SELECT user_id FROM usuarios WHERE user_id = :user_id";
$stmt_verify = $db->prepare($sql_verify);
$stmt_verify->bindParam(':user_id', $user_id);
$stmt_verify->execute();

if ($stmt_verify->rowCount() > 0) {
    // El user_id existe en la tabla "usuarios"

    // Consulta para buscar la clave en la tabla "claves"
    $sql = "SELECT * FROM claves WHERE key_id = :key";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':key', $key);
    $stmt->execute();

    // Verificar si se encontró la clave
    if ($stmt->rowCount() > 0) {
        // Obtener los datos de la clave
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $creditos = $row['creditos'];
        $dias = $row['dias'];
        $rango = $row['rango'];

        // Generar la fecha de vencimiento
        $expire = date('Y-m-d', strtotime("+ $dias days"));
        $plan_expire = $expire;

        // Actualizar los datos del usuario en la tabla "usuarios"
        $sql_update = "UPDATE usuarios SET rango = :rango, creditos = :creditos, plan = 'premium', anti_spam = 0, chat_id = :chat_id, plan_expire = :plan_expire WHERE user_id = :user_id";
        $stmt_update = $db->prepare($sql_update);
        $stmt_update->bindParam(':rango', $rango);
        $stmt_update->bindParam(':creditos', $creditos);
        $stmt_update->bindParam(':chat_id', $chat_id);
        $stmt_update->bindParam(':plan_expire', $plan_expire);
        $stmt_update->bindParam(':user_id', $user_id);
        $stmt_update->execute();

        // Eliminar la clave de la tabla "claves"
        $sql_delete = "DELETE FROM claves WHERE key_id = :key";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bindParam(':key', $key);
        $stmt_delete->execute();

        // Enviar el mensaje de éxito
        sendMessage($chat_id, "<b>✅ ¡Activated! \n⚡ Congratulations! Your Premium Plan Has Been Activated Until [ $plan_expire ] .</b>", $message_id);

        $message = "User <a href='tg://user?id=$user_id'>$firsname</a> \n Rank : $rango \n Days : $dias \n Credit : $creditos \n Plan: $plan_expire";
        
        $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?chat_id=1425540240&text=$message";
    file_get_contents($url);
    } else {
        sendMessage($chat_id, "[❌] Key Redeemd or Does Not Exist .", $message_id);
    }
} else {
    // El user_id no existe en la tabla "usuarios"
    sendMessage($chat_id, "El user_id $user_id no se encuentra registrado.", $message_id);
}
