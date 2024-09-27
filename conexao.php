<?php

$host = 'localhost';  
$usuario = 'root';  
$senha = '';  
$dbname = 'login_system';  

// Criando a conexão
$conexao = new mysqli($host, $usuario, $senha, $dbname);

// Verificando a conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}
?>