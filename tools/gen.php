<?php

$get_Card = clean(substr($message, 5));
$bin1 = substr($get_Card, 0, 6);

if (empty($bin1)) {
    sendMessage($chat_id, "<em>Error ⚠️%0A Correct Format => /gen 424242</em>", $message_id);
    exit();
}

$url = "https://binlist.io/lookup/$bin1/";

// Obtener los datos JSON de la API
$json = file_get_contents($url);
$objeto = json_decode($json);

// Extraer los valores que se desean almacenar
$iin = $objeto->number->iin;
$tipo = isset($objeto->type) ? $objeto->type : 'null';
$cate = isset($objeto->category) ? $objeto->category : 'null';
$pais = $objeto->country->name;
$flag = $objeto->country->emoji;
$banco = isset($objeto->bank->name) ? $objeto->bank->name : 'null';

if (empty($pais)) {
    sendMessage($chat_id, "<b>Add Valid Bin 💔</b>\n<em>Bin Invalid = 🏳️‍🌈</em>", $message_id);
    exit();
}

require_once 'recursos/gen_card.php';


$bin = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[0];
if(is_numeric($bin) & strlen($bin) == 5){
    echo "Same Card Number, Try Other Extra";
    exit();
}

    $mes = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[1];
   
    $ano = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[2];
   
    $cvv = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[3];

if(!is_numeric($cvv)){
    $cvv = '';
}
if(!is_numeric($mes)){
    $mes = '';
}
if(!is_numeric($ano)){
    $ano = '';
}
if(strlen($ano) == 2){
	$ano = "20$ano";
}


$gen = "🔮 <b>GEN CARDS [📇]</b> 🔮\n";
$gen .= "-----------------------------------\n";
$gen .= "💚 𝘽𝙄𝙉 ➜ <code>$iin</code> $pais $flag\n";
$gen .= "💌 𝙄𝙉𝙁𝙊 ➜ [$banco - $tipo - $cate]\n";
$gen .= "-----------------------------------\n";
$gen .= CCs($bin, $mes, $ano, $cvv)."\n";
$gen .= "-----------------------------------\n";
$gen .= "[🜲] 𝙐𝙎𝙀𝙍 ➜ @$username\n";
$gen .= "<b><em>BOT BY</em></b>$owner";

$keyboard = json_encode([
    "inline_keyboard" => [
        [
            ["text" => "Gen - Again ⚡", "callback_data" => "gen"]
        ]
    ]
]);

reply_to($chat_id, $message_id, $keyboard, $gen);
