<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'biblioteca_carti';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Eroare de conexiune: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>