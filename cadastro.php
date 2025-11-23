<?php
session_start();

// Inclui os Controllers necessários
require_once __DIR__ . '/controllers/usuarioController.php';
require_once __DIR__ . '/controllers/alunosModalidadeController.php';

$mensagem = '';
$usuarioController = new UsuarioController();
$inscricaoController = new AlunosModalidadeController();

// Busca a lista de modalidades para o dropdown
$modalidades_disponiveis = $inscricaoController->listarModalidadesDisponiveis();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Cria o usuário (agora retorna o user_id)
    $resultado_usuario = $usuarioController->registrar($_POST);
    $mensagem = $resultado_usuario['message'];

    if ($resultado_usuario['status'] == 'success') {
        
        $user_id = $resultado_usuario['user_id'];
        $modalidade_id = filter_input(INPUT_POST, 'modalidade_inicial', FILTER_VALIDATE_INT);
        
        // Inscreve o usuário na modalidade escolhida
        if ($modalidade_id) {
            $resultado_inscricao = $inscricaoController->processarInscricao($user_id, $modalidade_id);
            
            // Combina as mensagens de feedback
            $mensagem .= " e " . $resultado_inscricao['message'];
        } else {
            $mensagem .= " (Nenhuma modalidade inicial selecionada).";
        }
    }
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
            <p style="color: <?php echo (isset($resultado_usuario) && $resultado_usuario['status'] == 'success') ? 'green' : 'red'; ?>;">
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

            <h2>Escolha sua Modalidade Inicial:</h2>
            <select id="modalidade_inicial" name="modalidade_inicial">
                <option value="">(Opcional) Escolha uma Luta</option>
                <?php foreach ($modalidades_disponiveis as $modalidade): ?>
                    <option value="<?php echo $modalidade['id']; ?>">
                        <?php echo $modalidade['nome']; ?> (Gerente: <?php echo $modalidade['nome_gerente'] ?? 'N/A'; ?>)
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <button type="submit">Finalizar Cadastro e Inscrição</button>
        </form>

        <p>Já tem conta? <a href="login.php">Faça Login</a></p>
    </div>
</body>
</html>
