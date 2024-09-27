<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || !$_SESSION['logado']) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
</head>
<body>
    <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_logado']); ?>!</h2>
    <p>Você está logado no sistema.</p>
    <a href="logout.php">Sair</a>
    <br><br>
    <!-- Botão para editar os dados de login -->
    <form action="editar_usuario.php" method="get">
        <input type="submit" value="Alterar Dados do Login">
    </form>
</body>
</html>