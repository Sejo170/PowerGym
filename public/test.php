<?php
$host = 'localhost';
$usuario = 'josehernando_powergym';
$pass = 'PowerGym1234';
$bd = 'josehernando_powergym';
$puerto = 3306;

$mysqli = new mysqli($host, $usuario, $pass, $bd, $puerto);

if ($mysqli->connect_error) {
    die('Error de conexión: ' . $mysqli->connect_error . ' Código: ' . $mysqli->connect_errno);
}

echo '¡Conexión exitosa a MySQL! BD: ' . $bd;
$mysqli->close();
?>
