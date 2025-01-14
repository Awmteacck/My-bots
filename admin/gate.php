<?php

if($user_id != "5871846598"){
  exit();
}

$gate = trim(substr($message, 5));

if(empty($gate)){
 # echo "La variable \$gate está vacía";
  sendMessage($chat_id, "⚠ | Error Format Icorecct .", $message_id);
  exit();
}


$estado = 'si';

$conn = conectarBaseDatos();

$sql = "INSERT INTO gates (gate, estado) VALUES (:gate, :estado)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':gate', $gate);
$stmt->bindParam(':estado', $estado);

if ($stmt->execute()) {
    sendMessage($chat_id, "[HQ] New Gate Added Successfully [ @DEVPHPJS ] ✅.", $message_id);
} else {
    sendMessage($chat_id, "Error al agregar el gate.", $message_id);
}

$conn = null;