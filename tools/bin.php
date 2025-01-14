<?php

$lista = clean($message) ?: clean($r_message);

$lista = substr($lista,0,6);

$binData = getBinData($lista);

if (empty($binData['pais'])) {
    exit(sendMessage($chat_id, "<b>Add Good Bin</b>\n<em>Bin Invalid = 🏳️‍🌈</em>", $message_id));
}

$cmdbin = "BIN SEARCH [📇]
💚 Bin ➜ <code>$lista</code>

[🜲] Country ➜ {$binData['pais']} | {$binData['flag']}
[🜲] Type ➜ {$binData['moneda']} | {$binData['tipo']} | {$binData['level']}
[🜲] Bank ➜ {$binData['bank']}

[🜲] User ➜ @$username
[🜲] Owner ➜ $owner";

sendMessage($chat_id, $cmdbin, $message_id);
