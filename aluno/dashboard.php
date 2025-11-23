<?php
session_start();

// Proteção de rota: somente aluno pode acessar
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'aluno') {
    header("Location: ../login.php");
    exit;
}

// Inclui os controllers necessários
require_once __DIR__ . '/../controllers/alunosModalidadeController.php';
require_once __DIR__ . '/../controllers/aulaController.php';

$aluno_id = $_SESSION['user_id'];
$aluno_nome = $_SESSION['user_nome'];
$inscricaoController = new AlunosModalidadeController();
$aulaController = new AulaController();

// Busca inscrições ativas do aluno
$minhas_inscricoes = $inscricaoController->obterMinhasInscricoes($aluno_id);

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
        <h1>Bem-vindo, <?php echo $aluno_nome; ?>! 👋</h1>
        <p>Sua jornada nas artes marciais começa agora. Concentre-se nos seus treinos!</p>
        
        <hr>

        <h2>🥊 Suas Modalidades e Cronograma</h2>

        <?php if (!empty($minhas_inscricoes)): ?>
            <?php foreach ($minhas_inscricoes as $inscricao): ?>
                
                <h3>Modalidade: <?php echo $inscricao['nome']; ?></h3>
                <p>Gerente/Professor: <?php echo $inscricao['nome_gerente'] ?? 'N/A'; ?></p>

                <?php 
                    // Busca o cronograma para esta modalidade
                    $aulas_agendadas = $aulaController->getAulasPorModalidade($inscricao['modalidade_id']);
                ?>

                <h4>Horários Agendados:</h4>
                <?php if (!empty($aulas_agendadas)): ?>
                    <table>
                        <thead>
                            <tr><th>Dia</th><th>Horário</th><th>Instrutor</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($aulas_agendadas as $aula): ?>
                                <tr>
                                    <td><?php echo $aula['dia_semana']; ?></td>
                                    <td><?php echo date('H:i', strtotime($aula['horario'])); ?></td>
                                    <td><?php echo $aula['instrutor_nome']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Cronograma não definido para esta modalidade. Verifique com a administração.</p>
                <?php endif; ?>
                
                <hr>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você ainda não está inscrito em nenhuma luta. <a href="minhas_modalidades.php">Clique aqui para se inscrever.</a></p>
        <?php endif; ?>
        
        <h2>📍 Informações Úteis</h2>
        <p><strong>Local do FightZone:</strong> Av. Principal, 1234 - Centro.</p>
        <p><strong>Horário de Funcionamento Geral:</strong> Segunda a Sexta, 08:00 às 21:00.</p>
        
        <hr>

        <p><strong><a href="../logout.php">Sair do Sistema</a></strong></p>
    </div>
</body>
</html>
