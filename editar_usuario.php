<?php
session_start();
require 'conexao.php';  // Inclui o arquivo de conexão

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header("Location: index.php");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_usuario = $_POST['usuario'];
    $nova_senha = $_POST['senha'];
    $usuario_atual = $_SESSION['usuario_logado'];

    // Atualiza os dados do usuário no banco de dados
    if (!empty($nova_senha)) {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);  // Criptografa a nova senha
    }

    // Verifica se o nome de usuário já existe (exceto o usuário atual)
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ? AND usuario != ?");
    $stmt->bind_param("ss", $novo_usuario, $usuario_atual);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $erro = "O nome de usuário já está em uso!";
    } else {
        // Atualiza o nome de usuário e senha (se a senha for alterada)
        if (!empty($nova_senha)) {
            $stmt = $conexao->prepare("UPDATE usuarios SET usuario = ?, senha = ? WHERE usuario = ?");
            $stmt->bind_param("sss", $novo_usuario, $senha_hash, $usuario_atual);
        } else {
            $stmt = $conexao->prepare("UPDATE usuarios SET usuario = ? WHERE usuario = ?");
            $stmt->bind_param("ss", $novo_usuario, $usuario_atual);
        }

        if ($stmt->execute()) {
            // Atualiza a sessão com o novo nome de usuário
            $_SESSION['usuario_logado'] = $novo_usuario;
            $sucesso = "Dados atualizados com sucesso!";
        } else {
            $erro = "Erro ao atualizar os dados!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dados de Login</title>
</head>
<body>
    <h2>Editar Dados do Login</h2>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <?php if (isset($sucesso)) echo "<p style='color:green;'>$sucesso</p>"; ?>
    <form method="POST">
        <label for="usuario">Novo Usuário:</label><br>
        <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($_SESSION['usuario_logado']); ?>" required><br><br>
        <label for="senha">Nova Senha (deixe em branco para não alterar):</label><br>
        <input type="password" id="senha" name="senha"><br><br>
        <input type="submit" value="Salvar Alterações">
    </form>
    <br>
    <a href="welcome.php">Voltar</a>
</body>
</html>
