<?php


if ($user_id != "5871846598") {
   exit();
}

$lista = substr($message, 5);
$params = explode(",", $lista);

if (count($params) < 2 || count($params) > 3) {
    sendMessage($chat_id, 'Valor inválido /key {creditos},{rango},{dias}', $message_id);
    exit();
}

$creditos = $params[0];
$rango = $params[1];
$dias = isset($params[2]) ? $params[2] : null;

if (!is_numeric($creditos) || ($dias !== null && !is_numeric($dias))) {
    sendMessage($chat_id, '[❌] Invalid Command ~ /key {Credits},{Rank},{Days}', $message_id);
    exit();
}

if (empty($rango)) {
    $rango = "pro user";
}

$key_id = 'Shadow-' . bin2hex(random_bytes(6));

$cmdkey = "<em>
    Key Generated Successfully ✅
    ━━━━  ━━━━  ━━━  ━━━━━
    🔑 KEY ►►► <code>$key_id</code>  
    💎 Credits ►►►  $creditos
    🌟 Rank ►►► $rango 
    ⛈ Days ►►► $dias
    ━━━━  ━━━━  ━━━  ━━━━━
    [ ! ] Use The Cmd /claim To Redeem
    </em>
    $propietario";

sendMessage($chat_id, $cmdkey, $message_id);

$db = conectarBaseDatos();

// Preparar la consulta SQL
$sql = "INSERT INTO claves (key_id, creditos, rango, dias) VALUES (:key_id, :creditos, :rango, :dias)";

// Preparar la sentencia
$stmt = $db->prepare($sql);

// Asignar los valores de los parámetros
$stmt->bindParam(':key_id', $key_id);
$stmt->bindParam(':creditos', $creditos);
$stmt->bindParam(':rango', $rango);
$stmt->bindParam(':dias', $dias, PDO::PARAM_INT);

// Ejecutar la consulta
if ($stmt->execute()) {
    sendMessage($chat_id, "<em>Key Has Been Saved In Database ✅</em>", $message_id);
} else {
    sendMessage($chat_id, "Error al agregar los datos: " . $stmt->errorInfo()[2], $message_id);
}