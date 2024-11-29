<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "appcrud";
$port = 3306; // Porta padrão para MySQL

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
