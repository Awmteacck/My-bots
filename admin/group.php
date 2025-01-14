<?php
if ($user_id != "5871846598") {
    exit();
}

$list = trim(substr($message, 7));
$partes = explode(",", $list);

if (count($partes) !== 3) {
    sendMessage($chat_id, '<i>Formato incorrecto. El formato debe ser chat_id,creditos,dias</i>', $message_id);
    exit();
}

$id_chat = trim($partes[0]);
$creditos = trim($partes[1]);
$dias = trim($partes[2]);


$plan_expire = date('Y-m-d', strtotime("+ $dias days"));

sendMessage($chat_id, "--$id_chat--$creditos--$dias-", $message_id);

$conn = conectarBaseDatos();

$sql = "INSERT INTO usuarios (user_id, rango, creditos, anti_spam, plan, warns, registro, chat_id, plan_expire, last_message_time)
        VALUES (:user_id, 'supergroup', :creditos, 0, 'premium', 0, CURDATE(), :chat_id, :plan_expire, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $id_chat);
$stmt->bindParam(':creditos', $creditos);
$stmt->bindParam(':chat_id', $id_chat);
$stmt->bindParam(':plan_expire', $plan_expire);

if ($stmt->execute()) {

  // Obtener la fecha de vencimiento formateada
$fecha_vencimiento = date('d/m/Y', strtotime($plan_expire));

$mensaje .= "User Successfully Registered! 🎉🎉🎉\n";
$mensaje .= "Thank you for joining our premium service! 😊\n";
$mensaje .= "Your credits: $creditos 💳 \n";
$mensaje .= "Plan: premium ✅ \n";
$mensaje .= "Plan expiration date: $fecha_expiration 🗓️ \n";
$mensaje .= "Enjoy all the features and benefits we offer! 🚀🌟\n";

sendMessage($chat_id, $mensaje, $message_id);

} else {
    // Error al insertar datos
    sendMessage($chat_id, "Error al insertar datos: " . $stmt->errorInfo()[2], $message_id);
}

$conn = null;

