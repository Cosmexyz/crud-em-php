<?php
session_start();
require 'conexao.php';  // Inclui o arquivo de conexão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);  // Criptografa a senha

    // Verifica se o usuário já existe
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $erro = "Usuário já existe!";
    } else {
        // Insere o novo usuário no banco de dados
        $stmt = $conexao->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $senha);
        if ($stmt->execute()) {
            $_SESSION['logado'] = true;
            $_SESSION['usuario_logado'] = $usuario;
            header("Location: welcome.php");
            exit();
        } else {
            $erro = "Erro ao criar usuário!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário</title>
</head>
<body>
    <h2>Criar Usuário</h2>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <form method="POST">
        <label for="usuario">Usuário:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>
        <input type="submit" value="Criar">
    </form>

    <p>Já tem uma conta? <a href="index.php">Login</a></p>
</body>
</html>
