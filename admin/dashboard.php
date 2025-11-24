<?php
session_start();

// Proteção de rota: somente admin pode acessar
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../controllers/usuarioController.php';

$usuarioController = new UsuarioController();
$admin_nome = $_SESSION['user_nome'];

// Busca a lista completa de alunos
$todos_alunos = $usuarioController->obterTodosAlunosComModalidades();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - FightZone</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Bem-vindo, <?php echo $admin_nome; ?>! 👋</h1>
        <p>Aqui você tem a visão completa do sistema.</p>

        <hr>

        <h2>👥 Relatório de Todos os Alunos (Total: <?php echo count($todos_alunos); ?>)</h2>
        <?php if (!empty($todos_alunos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Modalidades Inscritas</th>
                        <th>Data de Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todos_alunos as $aluno): ?>
                        <tr>
                            <td><?php echo $aluno['nome']; ?></td>
                            <td><?php echo $aluno['email']; ?></td>
                            <td><?php echo $aluno['modalidades_inscritas'] ?: 'Nenhuma'; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($aluno['criado_em'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum aluno cadastrado no sistema ainda.</p>
        <?php endif; ?>

        <hr>

        <h2>⚙️ Opções do Sistema</h2>
        <ul>
            <li><a href="artes.php">Gerenciar Modalidades (CRUD)</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </div>
</body>
</html>

