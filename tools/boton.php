<?php

if ($data == "gen") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌");
    } else {
        $get_Card = substr($callback_bin, 5);

        $bin1 = substr($get_Card, 0, 6);

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

        require_once 'recursos/gen_card.php';
      
        $bin = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[0];
        if (is_numeric($bin) & strlen($bin) == 5) {
            echo "Same Card Number, Try Other Extra";
            exit();
        }

        $mes = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[1];

        $ano = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[2];

        $cvv = MultiExplodea([":", "|", "⋙", " ", "/"], $get_Card)[3];

        if (!is_numeric($cvv)) {
            $cvv = '';
        }
        if (!is_numeric($mes)) {
            $mes = '';
        }
        if (!is_numeric($ano)) {
            $ano = '';
        }
        if (strlen($ano) == 2) {
            $ano = "20$ano";
        }

       $gen = "🔮 <b>Shadow Generator</b> 🔮\n";
$gen .= "-----------------------------------\n";
$gen .= "💚 𝘽𝙄𝙉 ➜ <code>$iin</code> $pais $flag\n";
$gen .= "💌 𝙄𝙉𝙁𝙊 ➜ [$banco - $tipo - $cate]\n";
$gen .= "-----------------------------------\n";
$gen .= CCs($bin, $mes, $ano, $cvv) . "\n";
$gen .= "-----------------------------------\n";
$gen .= "[🜲] 𝙐𝙎𝙀𝙍 ➜ <a href='tg://user?id=$callback_user_id'>" . htmlspecialchars($callback_firstname . " " . $callback_lastname) . "</a>\n";
$gen .= "<b><em>BoT BY :</em></b> $owner";

$gen_encode = urlencode($gen);
      
        $reply_markup = json_encode([
            "inline_keyboard" => [
                [
                    ["text" => "Generar 🎲", "callback_data" => "gen"]
                ]
            ]
        ]);

        file_get_contents("".TELEGRAM_API_URL."/editMessageText?chat_id=$callback_chat_id&text=$gen_encode&message_id=$callback_message_id&parse_mode=HTML&reply_markup=$reply_markup");
    }
}




if ($data == "gates") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌");
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ['text' => '𝗖𝗵𝗮𝗿𝗴𝗲 💰', 'callback_data' => 'charged'],
                    ['text' => '𝗠𝗮𝘀𝘀𝗚𝗮𝘁𝗲𝘀 📊', 'callback_data' => 'mass'],
                    ["text" => "𝗔𝘂𝘁𝗵 🌝", "callback_data" => "auth"]
                ],
                [
                    ['text' => '𝗛𝗼𝗺𝗲 🏡', 'callback_data' => 'home']
                ],
            ]
        ];
        $caption = "
[🜲] -- 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 -- [🜲]

~> 10 𝗧𝗢𝗧𝗔𝗟 𝗚𝗔𝗧𝗘𝗦 🔥.
~> 11 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 𝗖𝗵𝗮𝗿𝗴𝗲𝗱 💎 .
~> 𝟏 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 𝗔𝘂𝘁𝗵 🔑 .
~> 𝟏 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 𝗠𝗮𝘀𝘀 ♻️ .";

        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $caption, $reply_markup);
    }
}

if ($data == "charged") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌");
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ['text' => '𝗠𝗮𝘀𝘀𝗚𝗮𝘁𝗲𝘀 📊', 'callback_data' => 'mass'],
                    ["text" => "𝟮𝗻𝗱 𝗣𝗮𝗴𝗲. ⇛", "callback_data" => "page2"]
                ],
                [
                    ['text' => '𝗛𝗼𝗺𝗲 🏡', 'callback_data' => 'home']
                ],
            ]
        ];
        $caption = "
[🜲] -- 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 -- [🜲]

[1] Command ~ /pp
Charge ~ 0.01$
Gateway ~ PayPal
Status ~ $on
----------------
[2] Command ~ /bb
Charge ~ 14$
Gateway ~ Shopify + Braintree
Status ~ ON 🟢
----------------
[3] Command ~ /pz
Gateway ~ Shopify + Payeezy
Cargo 14 usd
Status ~ $on
----------------
[3] Command ~ /bl
Charge ~ 18.97$
Gateway ~ Shopify + Bluepay
Status ~ $on
----------------
[4] Command ~ /ch
Charge ~ 8$
Gateway ~ Shopify + Chase
Status ~ ON 🟢
----------------
[5] Command ~ /mn
Charge ~ 18$
Gateway ~ Shopify + Moneris
Status ~ ON 🟢
----------------";

        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $caption, $reply_markup);
    }
}

if ($data == "page2") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌");
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ["text" => "𝗚𝗮𝘁𝗲𝘄𝗮𝘆𝘀 ✴️", "callback_data" => "gates"],
                    ["text" => "𝗔𝘂𝘁𝗵 🌝", "callback_data" => "auth"]
                ],
                [
                    ['text' => '𝗛𝗼𝗺𝗲 🏡', 'callback_data' => 'home']
                ],
            ]
        ];
        $caption = "
[🜲] -- 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 -- [🜲]

[6] Command ~ /st
Charge ~ 59.90$
Gateway ~ Stripe
Status ~ $on
----------------
[7] Command ~ /pi
Charge ~ $8.99$
Gateway ~ Shopify + vantiv
Status ~ $on
----------------
[8] Command ~ /sp
Gateway ~ Shopify
Charge ~ 6$
Status ~ $on
----------------
[9] Command ~ /
Charge ~ 0$
Gateway ~ Empty 
Status ~ $off
----------------
[10] Command ~ /
Charge ~ 0$
Gateway ~ Empty 
Status ~ $off
----------------
[11] Command ~ /
Charge ~ 0$
Gateway ~ Empty 
Status ~ $off
----------------
[12] Command ~ /
Charge ~ 0$
Gateway ~ Empty 
Status ~ $off
----------------";

        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $caption, $reply_markup);
    }
}

if ($data == "mass") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌");
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ["text" => "𝗔𝘂𝘁𝗵 🌝", "callback_data" => "auth"]
                ],
                [
                    ['text' => '𝗛𝗼𝗺𝗲 🏡', 'callback_data' => 'home']
                ],
            ]
        ];
        $caption = "
[🜲] -- 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 -- [🜲]

[1] Command ~ /mass
MAX ~ 10 Cards Checking
Charge ~ 0$
Gateway ~ Unknown
Status ~ OFF 🔴
----------------
[2] Command ~ /madd
MAX ~ 10 Cards Checking
Charge ~ 15$
Gateway ~ Braintree
Status ~ OFF 🔴
----------------";
        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $caption, $reply_markup);
    }
}

if ($data == "auth") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌");
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ["text" => "𝗚𝗮𝘁𝗲𝘄𝗮𝘆𝘀 ✴️", "callback_data" => "gates"]
                ],
                [
                    ['text' => '𝗛𝗼𝗺𝗲 🏡', 'callback_data' => 'home']
                ],
            ]
        ];
        $caption = "
[🜲] -- 𝗚𝗔𝗧𝗘𝗔𝗪𝗔𝗬𝗦 -- [🜲]

[1] Command ~ /au
Charge ~ 0$
Gateway ~ Stripe
Status ~ ON 🟢
----------------
[2] Command ~ /so
Charge ~ 0$
Gateway ~ Shopify
Status ~ $off
----------------";

        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $caption, $reply_markup);
    }
}

if ($data == "tools") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌"); 
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ['text' => '𝗚𝗮𝘁𝗲𝘄𝗮𝘆𝘀 ✴️', 'callback_data' => 'gates'],
                    ['text' => '𝗖𝗹𝗼𝘀𝗲 🔒', 'callback_data' => 'close']
                ],
                [
                    ["text" => "𝗢𝘁𝗵𝗲𝗿 𝗧𝗼𝗼𝗹𝘀 🧰", "callback_data" => "otros"],
                    ['text' => '𝗛𝗼𝗺𝗲 🏡', 'callback_data' => 'home']
                ],
            ]
        ];
        $caption = "
[🜲] -- 𝗦𝗛𝗔𝗗𝗢𝗪 𝗧𝗢𝗢𝗟𝗦 -- [🜲]
-----------------------
[1] Gen Cards [10] 💳
Command ➤ /gen
Status ~ 🟢
-----------------------
[2] Random Address
Command ➤ /fake
Status ~ 🟢
-----------------------
[3] Bin Lookup 
Comando ➤ /bin
Status ~ 🟢";
        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $caption, $reply_markup);
    }
}

if ($data == "home") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌"); 
    } else {
        $keyboard = [
            "inline_keyboard" => [
                [
                    ["text" => "𝗚𝗮𝘁𝗲𝘄𝗮𝘆𝘀 ✴️", "callback_data" => "gates"],
                    ["text" => "𝗧𝗼𝗼𝗹𝘀 🛠️", "callback_data" => "tools"],
                    ["text" => "𝗖𝗹𝗼𝘀𝗲 🔒", "callback_data" => "close"]
                ],
                [
                    ["text" => "𝗢𝘁𝗵𝗲𝗿 𝗧𝗼𝗼𝗹𝘀 🧰", "callback_data" => "otros"],
                    ["text" => "𝗚𝗿𝗼𝘂𝗯 𝗖𝗛𝗞 🪪", "url" => "https://t.me/IXI_Groub"]
                ]
            ]
        ];
        $freecmands = "🏡 𝗪𝗘𝗟𝗖𝗢𝗠𝗘 ~ $username";

        $reply_markup = json_encode($keyboard);

        editMessageCaption($callback_chat_id, $callback_message_id, $freecmands, $reply_markup);
    }
}

if ($data == "otros") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌"); 
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ['text' => '𝗚𝗮𝘁𝗲𝘄𝗮𝘆𝘀 ✴️', 'callback_data' => 'gates'],
                    ['text' => '𝗧𝗼𝗼𝗹𝘀 🛠️', 'callback_data' => 'tools']
                ],
                [
                    ['text' => '𝗛𝗼𝗺𝗲 🏡', 'callback_data' => 'home'],
                    ['text' => '𝗖𝗹𝗼𝘀𝗲 🔒', 'callback_data' => 'close']
                ],
            ]
        ];
        $caption = "
[🜲] -- 𝗦𝗛𝗔𝗗𝗢𝗪 𝗕𝗘𝗦𝗧 𝗧𝗢𝗢𝗟𝗦 -- [🜲]
------------------------------
[1] Translator [ English - Español ] 🌐
Command ➤ /tr
Status ~ 🟢
------------------------------
[2] Actividad Random 💽
Command ➤ /activity
Status ~ ❌
------------------------------
[3] Price BTC [USD-GBP-EUR]
Command ➤ /btc
Status ~ ✅
------------------------------
[4] TikTok Downloads
Command ➤ /tik
Status ~ ✅
------------------------------    
[5] Extractor Stripe Checkout
Comando ➤ /xt
Estado ✅
------------------------------
[6] IP lookup 
Command ➤ /ip
Status ~ ✅";

        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $caption, $reply_markup);
    }
}

if ($data == "close") {
    if ($callback_from != $callback_user_id) {
        answerCallbackQuery($callback_id, "Access denied. Start a session using /cmds. ❌"); 
    } else {
        $reply_markup = [
            'inline_keyboard' => [
                [
                    ['text' => '[🜲] 𝙊𝙬𝙣𝙚𝙧 - 𝗟𝘂𝗖𝗶𝗙𝗲𝗥 [ 🇪🇬 ]', 'url' => 'https://t.me/DEVPHPJS']
                ],
            ]
        ];
        $freecmands = "𝗖𝗟𝗢𝗦𝗘 💥";

        $reply_markup = json_encode($reply_markup);

        editMessageCaption($callback_chat_id, $callback_message_id, $freecmands, $reply_markup);
    }
}


