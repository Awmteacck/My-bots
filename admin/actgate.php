<?php

if($user_id != "5871846598"){
  exit();
}


$list = trim(substr($message, 5));
$partes = explode(",", $list);

$gate = trim($partes[0]);
$estado = trim($partes[1]);
$comentario = trim($partes[2]);


if(empty($comentario)){
  sendMessage($chat_id,'<i>[❌] Format Incorrect .</i>',$message_id);
  exit();
}

$db = conectarBaseDatos();

$sql = "UPDATE gates SET estado = :estado, comentario = :comentario WHERE gate = :gate";
$stmt = $db->prepare($sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $db->errorInfo()[2]);
}

$stmt->bindParam(':estado', $estado);
$stmt->bindParam(':comentario', $comentario);
$stmt->bindParam(':gate', $gate);

if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->errorInfo()[2]);
}

if ($stmt->rowCount() > 0) {
    sendMessage($chat_id,"Datos actualizados para el gate: $gate",$message_id);
} else {
    sendMessage($chat_id,"El gate $gate no existe en la base de datos.",$message_id);
}

