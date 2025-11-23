<?php
session_start();

// 1. Proteção de Rota (Crucial!)
// Verifica se o usuário está logado E se ele é do tipo 'aluno'
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'aluno') {
    header("Location: ../login.php");
    exit;
}

// Dados do usuário logado
$nome_aluno = $_SESSION['user_nome'];
$tipo_usuario = $_SESSION['user_tipo'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Aluno - FightZone</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo $nome_aluno; ?>! 👋</h1>
        <p>Você está logado como: **<?php echo $tipo_usuario; ?>**</p>
        
        <hr>

        <h2>🥊 Minhas Opções</h2>
        <ul>
            <li><a href="minhas_modalidades.php">Visualizar e Trocar Minhas Lutas</a></li>
            <li><a href="historico_aulas.php">Histórico de Aulas</a></li>
            <li><a href="../tutoriais.php">Tutoriais de Golpes</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
        
        <hr>
        
        <h3>Status Rápido</h3>
        <p>Nível atual: Iniciante (será buscado do banco em breve).</p>

    </div>
</body>
</html>

