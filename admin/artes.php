<?php
session_start();

// Proteção de rota: somente Admin e Gerente podem acessar
if (!isset($_SESSION['user_tipo']) || ($_SESSION['user_tipo'] !== 'admin' && $_SESSION['user_tipo'] !== 'gerente')) {
    header("Location: ../login.php");
    exit;
}

// Inclui os controllers necessários
require_once __DIR__ . '/../controllers/modalidadeController.php';
require_once __DIR__ . '/../controllers/usuarioController.php';

$modalidadeController = new ModalidadeController();
$usuarioController = new UsuarioController();
$mensagem = '';

// Processa o formulário de criação de modalidade
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST;
    
    // Se não foi selecionado um gerente, usa o ID do usuário logado
    if (!isset($data['gerente_id'])) {
        $data['gerente_id'] = $_SESSION['user_id'];
    }

    $resultado = $modalidadeController->criarModalidade($data);
    $mensagem = $resultado['message'];
}

// Busca todas as modalidades e gerentes
$modalidades = $modalidadeController->listarModalidades();
$gerentes = $usuarioController->listarPorTipo('gerente');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Artes - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciar Modalidades (Artes Marciais)</h1>
        <p>Logado como: **<?php echo $_SESSION['user_tipo']; ?>** (<?php echo $_SESSION['user_nome']; ?>)</p>
        
        <hr>

        <?php if ($mensagem): ?>
            <p style="color: <?php echo (isset($resultado) && $resultado['status'] == 'success') ? 'green' : 'red'; ?>;">
                <?php echo $mensagem; ?>
            </p>
        <?php endif; ?>

        <h2>➕ Cadastrar Nova Arte</h2>
        <form method="POST" action="artes.php">
            <label for="nome">Nome da Modalidade:</label>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="descricao">Descrição (Opcional):</label>
            <textarea id="descricao" name="descricao"></textarea><br><br>
            
            <label for="gerente_id">Gerente Responsável:</label>
            <select id="gerente_id" name="gerente_id" required>
                <option value="">Selecione um Gerente</option>
                <?php foreach ($gerentes as $gerente): ?>
                    <option value="<?php echo $gerente['id']; ?>">
                        <?php echo $gerente['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>
            
            <button type="submit">Cadastrar Modalidade</button>
        </form>

        <hr>

        <h2>📋 Lista de Modalidades Ativas</h2>
        <?php if (!empty($modalidades)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Gerente</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modalidades as $modalidade): ?>
                        <tr>
                            <td><?php echo $modalidade['id']; ?></td>
                            <td><?php echo $modalidade['nome']; ?></td>
                            <td><?php echo $modalidade['descricao']; ?></td>
                            <td><?php echo $modalidade['nome_gerente']; ?></td>
                            <td>[Editar] [Excluir]</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma modalidade cadastrada ainda.</p>
        <?php endif; ?>
        
        <p><a href="dashboard.php">Voltar ao Dashboard</a></p>
    </div>
</body>
</html>

