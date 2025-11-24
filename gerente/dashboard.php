<?php
session_start();

// Proteção de rota: somente gerente pode acessar
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'gerente') {
    header("Location: ../login.php");
    exit;
}

// Incluir controllers
require_once __DIR__ . '/../controllers/modalidadeController.php';
require_once __DIR__ . '/../controllers/usuarioController.php';

$gerente_id = $_SESSION['user_id'];
$gerente_nome = $_SESSION['user_nome'];

$modalidadeController = new ModalidadeController();
$usuarioController = new UsuarioController();

// Busca todas as modalidades gerenciadas por este gerente
$modalidades_gerenciadas = $modalidadeController->obterModalidadesDoGerente($gerente_id);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Gerente - FightZone</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Bem-vindo, <?php echo $gerente_nome; ?>! 👋</h1>
        <p>Você é o responsável pelas modalidades abaixo.</p>

        <hr>
        
        <?php if (!empty($modalidades_gerenciadas)): ?>
            <?php foreach ($modalidades_gerenciadas as $modalidade): ?>
                
                <h2>🥊 <?php echo $modalidade['nome']; ?></h2>
                <p>
                    Descrição: <?php echo $modalidade['descricao']; ?><br>
                    Gerente: <?php echo $modalidade['nome_gerente']; ?>
                </p>

                <?php 
                    // Para cada modalidade, lista os alunos inscritos
                    $alunos_inscritos = $usuarioController->obterAlunosPorModalidade($modalidade['id']);
                ?>

                <h3>👥 Alunos Matriculados (Total: <?php echo count($alunos_inscritos); ?>)</h3>
                
                <?php if (!empty($alunos_inscritos)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Data de Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alunos_inscritos as $aluno): ?>
                                <tr>
                                    <td><?php echo $aluno['nome']; ?></td>
                                    <td><?php echo $aluno['email']; ?></td>
                                    <td><?php echo isset($aluno['criado_em']) ? date('d/m/Y', strtotime($aluno['criado_em'])) : 'N/A'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nenhum aluno inscrito nesta modalidade ainda.</p>
                <?php endif; ?>

                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você ainda não foi designado como Gerente de nenhuma modalidade. Entre em contato com o Admin.</p>
        <?php endif; ?>

        <hr>

        <h2>⚙️ Opções</h2>
        <ul>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </div>
</body>
</html>

