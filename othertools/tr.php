<?php



$key = "1ce208c14520463eac8c9876c7884bba";
$endpoint = "https://api.cognitive.microsofttranslator.com";
$location = "brazilsouth";
$path = '/translate';
$constructed_url = $endpoint . $path;

$params = [
    'api-version' => '3.0',
    'to' => 'en',
];

$headers = [
    'Ocp-Apim-Subscription-Key' => $key,
    'Ocp-Apim-Subscription-Region' => $location,
    'Content-type' => 'application/json',
];

$body = [
    [
        'text' => 'null'
    ]
];

$options = [
    'http' => [
        'header'  => implode("\r\n", array_map(function ($key, $value) {
            return $key . ": " . $value;
        }, array_keys($headers), $headers)),
        'method'  => 'POST',
        'content' => json_encode($body),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($constructed_url . "?" . http_build_query($params), false, $context);
$response = json_decode($result, true);

// Convert the response array to JSON with JSON_UNESCAPED_UNICODE option
$json_response = json_encode($response, JSON_UNESCAPED_UNICODE);

// Print the JSON response
echo $json_response;
?>
