<?php
$host = "localhost";
$user = "root"; // tu usuario de MySQL en XAMPP
$pass = "";     // tu contraseña (vacía por defecto en XAMPP)
$db   = "chocopasteles";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
