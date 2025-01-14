<?php

$conn = conectarBaseDatos();

$sql = "SELECT * FROM usuarios WHERE user_id = :user_id";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Asignar el valor al parámetro :user_id
$stmt->bindParam(':user_id', $user_id);

// Ejecutar la consulta
$stmt->execute();

// Obtener los datos del usuario
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Extraer las variables necesarias del array $usuario
$rango = $usuario['rango'];
$creditos = $usuario['creditos'];
$anti_spam = $usuario['anti_spam'];
$plan = $usuario['plan'];
$warns = $usuario['warns'];
$registro = $usuario['registro'];
$plan_expire = $usuario['plan_expire'];
$last_message_time = $usuario['last_message_time'];

// Calcular los días restantes hasta la expiración del plan
$fecha_actual = date('Y-m-d');
$diferencia_dias = (strtotime($plan_expire) - strtotime($fecha_actual)) / (60 * 60 * 24);
$dias_restantes = ceil($diferencia_dias);

 $url = TELEGRAM_API_URL . "/getUserProfilePhotos?user_id=$user_id";
$foto = file_get_contents($url);
$file_id = getstr($foto, 'file_id":"', '"');
if (empty($file_id)) {
$file_id = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcStpdIxHh_jAya-73HMv7eqnYzOSz_SWhsdzg&usqp=CAU';
}



// Construir el mensaje con los datos del usuario 
$message = "•---- 𝗨𝗦𝗘𝗥 𝗗𝗔𝗧𝗔 -----•\n";
$message .= "🪪 𝗥𝗮𝗻𝗸 ↝ $rango\n";
$message .= "💰 𝗖𝗿𝗲𝗱𝗶𝘁𝘀 ↝ $creditos\n";
$message .= "❌ 𝗔𝗻𝘁𝗶𝗦𝗽𝗮𝗺 ↝ $anti_spam\n";
$message .= "💎 𝗣𝗹𝗮𝗻 ↝ $plan\n";
$message .= "⚠️ 𝗪𝗮𝗿𝗻𝘀 ↝ $warns\n";
$message .= "📚 𝗟𝗼𝗴 ↝ $registro\n";
$message .= "📅 𝗣𝗹𝗮𝗻𝗘𝘅𝗽𝗶𝗿𝗲 ↝ $plan_expire [$dias_restantes days]\n";
$message .= "📄 𝗟𝗮𝘀𝘁𝗠𝗲𝘀𝘀𝗮𝗴𝗲 ↝ $last_message_time\n";

// Cerrar la conexión a la base de datos
$conn = null;

// Enviar el mensaje al chat
sendPhoto($chat_id, $file_id, $message, $message_id);
