<?php

$link = substr($message, 5);


if(empty($link)){
  sendMessage($chat_id, "<b>[❌] Error : Format Incorrect | </b>\n/tik URL");

  exit();
}





$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://tiktok-downloader-download-tiktok-videos-without-watermark.p.rapidapi.com/vid/index?url=$link",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: tiktok-downloader-download-tiktok-videos-without-watermark.p.rapidapi.com",
		"X-RapidAPI-Key: bf13d1f285mshd843cabc02df7e3p1383b2jsn49e4b06995d8"
	],
]);

$zoro = curl_exec($curl);

$z = json_decode($zoro);

$video = $z->video[0];
$music = $z->music[0];
$original = $z->OriginalWatermarkedVideo[0];
$dinamic = $z->dynamic_cover[0];
$autor = $z->author[0];
$region = $z->region[0];

sendVideo($chat_id, $video, "<b>Successful Download ✅ \n Author : $autor \n Región : $region\nRequested By :</b> @$username [<b>$rango</b>]", $keyboard = null, $message_id);