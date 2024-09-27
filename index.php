<?php
session_start();
require 'conexao.php';  // Inclui o arquivo de conexão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Verifica se o usuário existe no banco de dados
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuarioData = $resultado->fetch_assoc();

        // Verifica se a senha está correta
        if (password_verify($senha, $usuarioData['senha'])) {
            $_SESSION['logado'] = true;
            $_SESSION['usuario_logado'] = $usuario;
            header("Location: welcome.php");
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <form method="POST">
        <label for="usuario">Usuário:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>
        <input type="submit" value="Login">
    </form>

    <p>Ainda não tem uma conta? <a href="register.php">Criar usuário</a></p>
</body>
</html>
