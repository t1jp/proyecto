<?php
require _DIR_ . '/vendor/autoload.php';

// Configuración de las credenciales
$credenciales = _DIR_ . '/ruta/al/archivo/credenciales.json';

// Crea un nuevo cliente de Google
$cliente = new Google_Client();
$cliente->setApplicationName('Mi Proyecto');
$cliente->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
$cliente->setAuthConfig($credenciales);
$cliente->setAccessType('offline');
$cliente->setPrompt('select_account consent');

// Autenticación
$tokenPath = 'token.json';

if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $cliente->setAccessToken($accessToken);
}

if ($cliente->isAccessTokenExpired()) {
    if ($cliente->getRefreshToken()) {
        $cliente->fetchAccessTokenWithRefreshToken($cliente->getRefreshToken());
    } else {
        $authUrl = $cliente->createAuthUrl();
        echo "Visita la siguiente URL y autoriza la aplicación:\n\n$authUrl\n\n";
        echo "Después de autorizar, introduce el código de verificación: ";
        $authCode = trim(fgets(STDIN));
        $accessToken = $cliente->fetchAccessTokenWithAuthCode($authCode);
        $cliente->setAccessToken($accessToken);

        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }
    }
    if (!file_exists(dirname($tokenPath))) {
        mkdir(dirname($tokenPath), 0700, true);
    }
    file_put_contents($tokenPath, json_encode($cliente->getAccessToken()));
}

// Crea el servicio de Google Calendar
$servicio = new Google_Service_Calendar($cliente);

// Ejemplo: Obtener eventos
$eventos = $servicio->events->listEvents('primary');
foreach ($eventos->getItems() as $evento) {
    echo $evento->getSummary()."\n";
}

// Ejemplo: Crear un nuevo evento
$nuevoEvento = new Google_Service_Calendar_Event([
    'summary' => 'Nuevo evento',
    'start' => ['dateTime' => '2023-06-03T10:00:00'],
    'end' => ['dateTime' => '2023-06-03T12:00:00'],
]);

$resultado = $servicio->events->insert('primary', $nuevoEvento);
echo "Evento creado con el ID: " . $resultado->getId();
?>