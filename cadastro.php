<?php
session_start();

// Inclui o Controller para processar o formulário
require_once __DIR__ . '/controllers/usuarioController.php';

$mensagem = '';
$usuarioController = new UsuarioController();

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Chama a função de registro do Controller
    $resultado = $usuarioController->registrar($_POST);

    // Define a mensagem de feedback para o usuário
    $mensagem = $resultado['message'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - FightZone</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Novo Aluno</h1>

        <?php if ($mensagem): ?>
            <p style="color: <?php echo (isset($resultado) && $resultado['status'] == 'success') ? 'green' : 'red'; ?>;">
                <?php echo $mensagem; ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="cadastro.php">
            
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br><br>

            <button type="submit">Finalizar Cadastro</button>
        </form>

        <p>Já tem conta? <a href="login.php">Faça Login</a></p>
    </div>
</body>
</html>

