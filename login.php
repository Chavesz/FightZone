<?php
session_start();

// Verifica se o usuário JÁ está logado
if (isset($_SESSION['user_tipo'])) {
    // Redireciona imediatamente para o dashboard correto
    $tipo = $_SESSION['user_tipo'];
    header("Location: {$tipo}/dashboard.php");
    exit;
}

// Inclui o Controller para processar o formulário
require_once __DIR__ . '/controllers/usuarioController.php';

$mensagem = '';
$usuarioController = new UsuarioController();

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Chama a função de login do Controller
    $resultado = $usuarioController->logar($_POST);

    // Verifica o resultado do Controller
    if ($resultado['status'] == 'success') {
        // Redireciona para o dashboard específico
        $tipo = $resultado['tipo'];
        header("Location: {$tipo}/dashboard.php");
        exit;
    } else {
        $mensagem = $resultado['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - FightZone</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-box">
        <h1>Login - FightZone</h1>

        <?php if ($mensagem): ?>
            <p style="color: red;">
                <?php echo $mensagem; ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br><br>

            <button type="submit">Entrar</button>
        </form>

        <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
</body>
</html>

