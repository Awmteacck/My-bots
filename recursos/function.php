<?php

/*function conectarBaseDatos() {
    $host = 'dpg-cjeko6avvtos73f8libg-a.oregon-postgres.render.com';
    $port = '3306';
    $database = 'botusers';
    $user = 'luciferbot';
    $password = 'ltzufdsjLLf3lpAGPIz7RxTxMQCBZpdf';

    $dsn = "pgsql:host=$host;port=$port;dbname=$database";

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}*/



function conectarBaseDatos() {
    $host = 'sql8.freesqldatabase.com';
    $port = '3306';
    $database = 'sql8640302';
    $user = 'sql8640302';
    $password = 'cUYrZxW2mU';

    $dsn = "mysql:host=$host;port=$port;dbname=$database";

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
/*

function conectarBaseDatos() {
    $host = 'aws.connect.psdb.cloud';
    $database = 'rudeos';
    $user = 'vkw2tm5ll8me2pgax08t';
    $password = 'pscale_pw_aU3yr2iRDSQq7DdMFN9sc9UZExL3NOZ73P0jHfSin1H';
    $port = 3306;

    $sslOptions = [
        PDO::MYSQL_ATTR_SSL_CA => openssl_get_cert_locations()['default_cert_file'],
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true
    ];

    $dsn = "mysql:host=$host;dbname=$database;port=$port";

    try {
        $db = new PDO($dsn, $user, $password, $sslOptions);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
*/
function verificarGate($gate, $chat_id, $message_id)
{
    $db = conectarBaseDatos();

    $sql = "SELECT estado, comentario FROM gates WHERE gate = :gate";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':gate', $gate);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        sendMessage($chat_id, "El gate no existe.", $message_id);
        exit();
    } else {
        $estado = $row['estado'];
        $comentario = $row['comentario'];

        if ($estado === 'no') {
            sendMessage($chat_id, $comentario, $message_id);
            exit();
        }
    }
}

function userData($user_id,$username,$chat_id,$message_id)
{
    $db = conectarBaseDatos();

    $sql = "SELECT rango, creditos, anti_spam, plan, warns, last_message_time FROM usuarios WHERE user_id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
       sendMessage($chat_id, "⚠ @$username [<code>$user_id</code>] YOU ARE NOT REGISTERED IN THE DATABASE PLEASE USE CMD /register", $message_id);
      exit();
    } else {
        return $row; // Devuelve los datos del usuario como arreglo asociativo
    }
}


function groupPlan($chat_id, $message_id)
{
    $db = conectarBaseDatos();

    $sql = "SELECT plan, creditos FROM usuarios WHERE chat_id = :chat_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':chat_id', $chat_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        sendMessage($chat_id, 'Ups, your group is not registered 😥', $message_id);
        exit();
    }

    $plan = $row['plan'];
    $creditos = $row['creditos'];

    if ($plan !== 'premium') {
        sendMessage($chat_id, 'Ups, your group does not have a PREMIUM plan 😥', $message_id);
        exit();
    }

    return $creditos;
}



function updateTime($user_id) {
    $db = conectarBaseDatos();

    // Obtener la fecha y hora actual
    $last_message_time = date('Y-m-d H:i:s');

    // Preparar la consulta SQL
    $sql = "UPDATE usuarios SET last_message_time = :last_message_time WHERE user_id = :user_id";

    // Preparar la sentencia
    $stmt = $db->prepare($sql);

    // Asignar los valores de los parámetros
    $stmt->bindParam(':last_message_time', $last_message_time);
    $stmt->bindParam(':user_id', $user_id);

    // Ejecutar la consulta
    $stmt->execute();
}

function restarCreditos($chat_id)
{
    $conexion = conectarBaseDatos();

    // Consulta para obtener los créditos actuales del chat
    $sql = "SELECT creditos FROM usuarios WHERE chat_id = :chat_id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':chat_id', $chat_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $creditosActuales = $fila['creditos'];

        // Verificar si hay suficientes créditos para restar
        if ($creditosActuales >= 2) {
            $creditosNuevos = $creditosActuales - 2;

            // Actualizar los créditos en la tabla usuarios
            $sql = "UPDATE usuarios SET creditos = :creditosNuevos WHERE chat_id = :chat_id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':creditosNuevos', $creditosNuevos);
            $stmt->bindParam(':chat_id', $chat_id);
            if ($stmt->execute()) {
                // Créditos restados exitosamente
                $conexion = null;
                return true;
            }
        }
    }

    $conexion = null;
    return false;
}


function validateCreditCard($cc, $cvv, $mes, $ano, $gate, $gateName, $chat_id, $message_id)
{
    $validCards = [
        '4' => ['length' => 16, 'cvvLength' => 3],
        '5' => ['length' => 16, 'cvvLength' => 3],
        '6' => ['length' => 16, 'cvvLength' => 3],
        '3' => ['length' => 15, 'cvvLength' => 4],
    ];

    $currentMonth = date('m');
    $currentYear = date('Y');

    $ccFirstDigit = substr($cc, 0, 1);

    if (
        !isset($validCards[$ccFirstDigit]) ||
        strlen($cc) !== $validCards[$ccFirstDigit]['length'] ||
        strlen($cvv) !== $validCards[$ccFirstDigit]['cvvLength']
    ) {
        $errorMessage = "<b>$gateName 💹</b>\n⚠ Credit card is invalid. Please use the correct format: /$gate cc|month|year|cvv.";
        sendMessage($chat_id, $errorMessage, $message_id);
        exit();
    }

    $expirationDate = DateTime::createFromFormat('Y-m', $ano . '-' . $mes);
    $currentDate = new DateTime();

    if ($expirationDate < $currentDate) {
        $errorMessage = "<b>$gateName 💹</b>\n⚠ Expiration date is invalid. Please use a valid date.";
        sendMessage($chat_id, $errorMessage, $message_id);
        exit();
    }
}


function getBinData($ccbin)
{
    $binlist_url = 'https://lookup.binlist.net/' . $ccbin;
    $binlist_response = file_get_contents($binlist_url);
    $binlist_data = json_decode($binlist_response, true);

    $binData = array(
        'bin' => $binlist_data['number']['scheme'] ?? null,    
        'tipo' => $binlist_data['type'] ?? null,
        'level' => $binlist_data['prepaid'] ? 'prepaid' : 'non-prepaid',
        'banco' => $binlist_data['bank']['name'] ?? null,
        'country' => $binlist_data['country']['numeric'] ?? null,
        'pais' => $binlist_data['country']['name'] ?? null,
        'flag' => $binlist_data['country']['emoji'] ?? null,
        'moneda' => $binlist_data['country']['currency'] ?? null,
        'countryUnicode' => $binlist_data['country']['emojiUnicode'] ?? null,
        'countryCode' => $binlist_data['country']['alpha2'] ?? null
    );

    return $binData;
}

function multiexplode($delimiters, $string)
{
    $one = str_replace($delimiters, $delimiters[0], $string);
    $two = explode($delimiters[0], $one);
    return $two;
}

function clean($string)
{
    $text = preg_replace("/\r|\n/", " ", $string);
    $str1 = preg_replace('/\s+/', ' ', $text);
    $str = preg_replace("/[^0-9]/", " ", $str1);
    $string = trim($str, " ");
    $lista = preg_replace('/\s+/', ' ', $string);
    return $lista;
}

function time1($val)
{
    $endtime = microtime(true);
    $time = $endtime - $val;
    $time = substr($time, 0, 4);
    return $time;
}

function bannedbin($bin)
{
    $bugbin = file_get_contents('banned.txt');
    $exploded = explode("\n", $bugbin);
    if (in_array($bin, $exploded)) {
        return true;
    }
}

function luhn_check($number)
{
    $number = preg_replace('/[^\d]/', '', $number);
    $sum = 0;

    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $digit = substr($number, $i, 1);
        $sum += $i % 2 ? $digit : array_sum(str_split($digit * 2));
    }

    return ($sum % 10 === 0);
}

function getstr($string, $start, $end)
{
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}

