<?php
 header('Content-Type: application/json');



$url = 'http://158.69.119.209/data/index.php?view=processlogin';
$headers = [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'Accept-Language: es-ES,es;q=0.9',
    'Cache-Control: max-age=0',
    'Connection: keep-alive',
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie: PHPSESSID=t8p38ih0nq7a826l4qrbfjbms4',
    'Origin: http://158.69.119.209',
    'Referer: http://158.69.119.209/data/index.php',
    'Upgrade-Insecure-Requests: 1',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
];

$data = 'usuario=kbpo20&contrasena=71X52C5317';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disabling SSL verification
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);

if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    echo $response;
}

curl_close($ch);





$url = 'http://158.69.119.209/data/index.php?view=home';
$headers = [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'Accept-Language: es-ES,es;q=0.9',
    'Connection: keep-alive',
    'Cookie: PHPSESSID=t8p38ih0nq7a826l4qrbfjbms4',
    'Referer: http://158.69.119.209/data/index.php?view=processlogin',
    'Upgrade-Insecure-Requests: 1',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$output = curl_exec($ch);

if ($output === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo $output;
}

exit();


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://158.69.119.209/data/index.php?action=validate');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: */*',
    'Accept-Language: es-ES,es;q=0.9',
    'Connection: keep-alive',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Origin: http://158.69.119.209',
    'Referer: http://158.69.119.209/data/index.php?view=home',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
    'X-Requested-With: XMLHttpRequest',
    'Accept-Encoding: gzip',
]);
#curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=t8p38ih0nq7a826l4qrbfjbms4');
curl_setopt($ch, CURLOPT_POSTFIELDS, 'tipo_bus=1&valor_buscado=48358300&valor_ruc=&valor_numero=&materno_busqueda=&nombre_busqueda=&paterno_busqueda=&ubicacion_busqueda=');
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie99.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie99.txt');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$r2 = curl_exec($ch);

curl_close($ch);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://158.69.119.209/data/index.php?view=mostrar&cod=48358300');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'Accept-Language: es-ES,es;q=0.9',
    'Connection: keep-alive',
    'Referer: http://158.69.119.209/data/index.php?view=home',
    'Upgrade-Insecure-Requests: 1',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
    'Accept-Encoding: gzip',
]);
curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookie99.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/cookie99.txt');
#curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=t8p38ih0nq7a826l4qrbfjbms4');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$r3 = curl_exec($ch);

curl_close($ch);


echo "<textarea>$r3</textarea>";
