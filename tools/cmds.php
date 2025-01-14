<?php


$keyboardx = json_encode([
    "inline_keyboard" => [
        [
            ["text" => "𝗚𝗮𝘁𝗲𝘄𝗮𝘆𝘀 ✴️", "callback_data" => "gates"],
            ["text" => "𝗧𝗼𝗼𝗹𝘀 🛠️", "callback_data" => "tools"],
            ["text" => "𝗖𝗹𝗼𝘀𝗲 🔒", "callback_data" => "close"]
        ],
        [
            ["text" => "𝗢𝘁𝗵𝗲𝗿 𝗧𝗼𝗼𝗹𝘀 🧰", "callback_data" => "otros"],
            ["text" => "𝗢𝗪𝗡𝗘𝗥 👑", "url" => "https://t.me/IXI_Groub"]
        ]
    ]
]);






$video = 'http://rudibot.alwaysdata.net/v1.mp4';

sendVideo($chat_id, $video, "💬 𝗪𝗘𝗟𝗖𝗢𝗠𝗘 - $firstname", $keyboardx, $message_id);


