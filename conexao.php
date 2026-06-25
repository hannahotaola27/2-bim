<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "huguinho";

$conn = new mysqli($host, $user, $pass, $db);

// charset (EVITA PROBLEMAS DE SEGURANÇA)
$conn->set_charset("utf8mb4");

if($conn->connect_error){
    die("Erro na conexão com o banco.");
}
?>