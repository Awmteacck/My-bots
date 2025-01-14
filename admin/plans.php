<?php

date_default_timezone_set('America/Lima');

$db = conectarBaseDatos();

// Consulta SQL para buscar usuarios cuyo plan expire en los próximos 3 días o ya ha expirado
$sql = "SELECT user_id, plan_expire FROM usuarios WHERE plan_expire IS NOT NULL AND (plan_expire <= CURDATE() OR plan_expire BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY))";
$stmt = $db->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$usuariosAdvertidos = 0;
$usuariosVencidos = 0;

$botToken = '6493593049:AAHk36nfVhelewkWWm28L8CcltgyPBeRghw';
foreach ($usuarios as $usuario) {
    $user_id = $usuario['user_id'];
    $plan_expire = new DateTime($usuario['plan_expire']);

    $fecha_actual = new DateTime();
    $diferencia = $fecha_actual->diff($plan_expire);
    $dias_restantes = $diferencia->days;

    if ($diferencia->invert == 1 || $dias_restantes <= 3) {
        if ($diferencia->invert == 1) {
            // Plan ha expirado
            $message = "[❌] Your Plan Has Expired ./nPlease Renew it To Continue Enjoying Our Services .";
            $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$user_id&text=".urlencode($message);
            file_get_contents($url);

            // Actualizar los datos del usuario
            $sql_update = "UPDATE usuarios SET plan = 'basico', anti_spam = 20, plan_expire = DATE_ADD(CURDATE(), INTERVAL 1 YEAR), last_message_time = NOW() WHERE user_id = :user_id";
            $stmt_update = $db->prepare($sql_update);
            $stmt_update->bindParam(':user_id', $user_id);
            $stmt_update->execute();

            $usuariosVencidos++;
        } elseif ($dias_restantes <= 3) {
            // Plan está próximo a vencer
            $message = "Remember That You Have $dias_restantes Days Until Your Plan Expires. Renew Your Plan To Avoid Interrupting The Service .";
            $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$user_id&text=".urlencode($message);
            file_get_contents($url);

            $usuariosAdvertidos++;
        }
    }
}

$db = null;

sendMessage($chat_id, "User Warns ~ $usuariosAdvertidos /nUser Expire ~ $usuariosVencidos", $message_id);