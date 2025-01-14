<?php


if($user_id != "5871846598"){
  exit();
}

$list = trim(substr($message, 5));
$partes = explode(",", $list);

if (count($partes) !== 2) {
    sendMessage($chat_id, '<i>[❌] Format Incorrect .</i>', $message_id);
    exit();
}

$user_id_buscar = trim($partes[0]);
$cantidad_advertencias_a_sumar = trim($partes[1]);


// Cambia este valor a la cantidad de advertencias que deseas sumar

$db = conectarBaseDatos();

// Consulta SQL para buscar el usuario por su ID
$sql = "SELECT warns FROM usuarios WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id_buscar);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    // El usuario existe, obtén el valor actual de advertencias
    $advertenciasActuales = $usuario['warns'];

    // Aumentar el número de advertencias según la cantidad especificada
    $nuevasAdvertencias = $advertenciasActuales + $cantidad_advertencias_a_sumar;

    // Actualizar el valor de advertencias en la base de datos
    $sql_update = "UPDATE usuarios SET warns = :nuevas_advertencias WHERE user_id = :user_id";
    $stmt_update = $db->prepare($sql_update);
    $stmt_update->bindParam(':nuevas_advertencias', $nuevasAdvertencias);
    $stmt_update->bindParam(':user_id', $user_id_buscar);
    $stmt_update->execute();

    // Almacena el nuevo valor de advertencias en una variable para su uso posterior
    $advertenciasActualizadas = $nuevasAdvertencias;

    // Envía un mensaje confirmando el aumento de advertencias
    sendMessage($chat_id, "Se han sumado $cantidad_advertencias_a_sumar advertencias al usuario con ID $user_id_buscar. Ahora tiene un total de $advertenciasActualizadas advertencias.", $message_id);
} else {
    // El usuario no existe en la base de datos
    sendMessage($chat_id, "El usuario con ID $user_id_buscar no existe en la base de datos.", $message_id);
}

$db = null;
?>
