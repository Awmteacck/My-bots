<?php


$lista = substr($message, 4);

#$ip = substr($lista, 0,6);

if (empty($lista)) {
	sendMessage($chat_id, "<em>Intente nuevamente⚠️\nUse /ip 1.1.1.1</em>", $message_id);
	exit();
}

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://ipwhois.app/widget.php?ip=$lista&lang=en",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	#CURLOPT_POSTFIELDS => "ip=$lista&lang=en",
	CURLOPT_HTTPHEADER => [
		"Accept: */*",
		"Accept-Language: es-ES,es;q=0.9",
		"Connection: keep-alive",
		"Host: ipwhois.app",
		"Origin: https://ipwhois.io",
		"Referer: https://ipwhois.io/"
	],
]);
$r22 = curl_exec($curl);
curl_close($curl);


#sendMessage($chat_id," ....$r22", $message_id);



$obj = json_decode($r22);

$ip = $obj->ip;
$type = $obj->type;
$continent = $obj->continent;
$continent_code = $obj->continent_code;
$country = $obj->country;
$country_code = $obj->country_code;
$region = $obj->region;
$city = $obj->city;
$postal = $obj->postal;
$capital = $obj->capital;

$emoji = $obj->flag->emoji;

$anonymous = $obj->security->anonymous ? 'si' : 'no';
$proxy = $obj->security->proxy ? 'si' : 'no';
$vpn = $obj->security->vpn ? 'si' : 'no';
$tor = $obj->security->tor ? 'si' : 'no';
$hosting = $obj->security->hosting  ? 'si' : 'no';

$name = $obj->currency->name;
$code = $obj->currency->code;
$symbol = $obj->currency->symbol;
$plural = $obj->currency->plural;
$exchange_rate = $obj->currency->exchange_rate;

$id = $obj->timezone->id;
$abbr = $obj->timezone->abbr;
$is_dst = $obj->timezone->is_dst;
$offset = $obj->timezone->offset;
$utc = $obj->timezone->utc;
$current_time = $obj->timezone->current_time;

$org = $obj->connection->org;
$dominio = $obj->connection->domain;




$cmdip = "<b>IP Geolocation[🌍] </b>
▬▬▬▬▬▬▬▬▬▬▬▬▬
[♪] ip ⇨  $ip  [♪] type ⇨ $type
[♪] continent ⇨ $continent | $continent_code
[♪] country ⇨ $country [$emoji] | $country_code
[♪] region ⇨ $region [♪] city ⇨ $city
[♪] postal ⇨ $postal [♪] capital ⇨ $capital
[♪] anonymous ⇨ $anonymous [♪] proxy ⇨ $proxy 
[♪] tor ⇨ $tor [♪] vpn ⇨ $vpn 
[♪] hosting ⇨ $hosting[♪] moneda ⇨ $name
[♪] time_zone ⇨ $id 
[♪] org ⇨ $org [♪] dominio ⇨ $dominio
▬▬▬▬▬▬▬▬▬▬▬▬▬
[♪] checked by ⇨ @$username
[♪] bot by ⇨ $owner";

sendMessage($chat_id, $cmdip, $message_id);
