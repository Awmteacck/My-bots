<?php

if ($user_id != "5871846598") {
    exit();
}


$lista = substr($message, 5);

if(empty($lista)){
  sendMessage($chat_id,'error: sms empty',$message_id);
  exit();
}


$db = conectarBaseDatos();

// Consulta SQL para seleccionar todos los usuarios cuyo "user_id" no comienza con "-"
$sql = "SELECT user_id FROM usuarios WHERE user_id NOT LIKE '-%'";
$stmt = $db->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    $user_id = $usuario['user_id'];

    // Envía el mensaje solo a los usuarios cuyo "user_id" no comienza con "-"
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage?chat_id=$user_id&text=" . urlencode($lista);
    file_get_contents($url);
}

$db = null;
?>
