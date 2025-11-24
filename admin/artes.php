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
$modalidade_editar = null;
$editar_id = null;

// Processa exclusão
if (isset($_GET['excluir'])) {
    $id = filter_input(INPUT_GET, 'excluir', FILTER_VALIDATE_INT);
    if ($id) {
        $resultado = $modalidadeController->excluirModalidade($id);
        $mensagem = $resultado['message'];
        header("Location: artes.php?msg=" . urlencode($mensagem) . "&status=" . $resultado['status']);
        exit;
    }
}

// Processa edição - busca modalidade para editar
if (isset($_GET['editar'])) {
    $id = filter_input(INPUT_GET, 'editar', FILTER_VALIDATE_INT);
    if ($id) {
        $modalidade_editar = $modalidadeController->obterModalidadePorId($id);
        $editar_id = $id;
    }
}

// Processa o formulário (criação ou edição)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST;
    
    // Se não foi selecionado um gerente, usa o ID do usuário logado
    if (!isset($data['gerente_id'])) {
        $data['gerente_id'] = $_SESSION['user_id'];
    }

    // Verifica se é edição ou criação
    if (isset($data['editar_id']) && !empty($data['editar_id'])) {
        $resultado = $modalidadeController->atualizarModalidade($data['editar_id'], $data);
        $mensagem = $resultado['message'];
        header("Location: artes.php?msg=" . urlencode($mensagem) . "&status=" . $resultado['status']);
        exit;
    } else {
        $resultado = $modalidadeController->criarModalidade($data);
        $mensagem = $resultado['message'];
    }
}

// Verifica mensagem da URL (após redirect)
if (isset($_GET['msg'])) {
    $mensagem = $_GET['msg'];
    $resultado = ['status' => $_GET['status'] ?? 'success'];
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
    <div class="dashboard-container">
        <h1>Gerenciar Modalidades (Artes Marciais)</h1>
        <p class="user-info">Logado como: <strong><?php echo $_SESSION['user_tipo']; ?></strong> (<?php echo $_SESSION['user_nome']; ?>)</p>
        
        <hr>

        <?php if ($mensagem): ?>
            <p style="color: <?php echo (isset($resultado) && $resultado['status'] == 'success') ? 'green' : 'red'; ?>;">
                <?php echo $mensagem; ?>
            </p>
        <?php endif; ?>

        <h2><?php echo $editar_id ? '✏️ Editar Modalidade' : '➕ Cadastrar Nova Arte'; ?></h2>
        <form method="POST" action="artes.php">
            <?php if ($editar_id): ?>
                <input type="hidden" name="editar_id" value="<?php echo $editar_id; ?>">
            <?php endif; ?>
            
            <label for="nome">Nome da Modalidade:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $modalidade_editar['nome'] ?? ''; ?>" required>

            <label for="descricao">Descrição (Opcional):</label>
            <textarea id="descricao" name="descricao"><?php echo $modalidade_editar['descricao'] ?? ''; ?></textarea>
            
            <label for="gerente_id">Gerente Responsável:</label>
            <select id="gerente_id" name="gerente_id" required>
                <option value="">Selecione um Gerente</option>
                <?php foreach ($gerentes as $gerente): ?>
                    <option value="<?php echo $gerente['id']; ?>" 
                        <?php echo (isset($modalidade_editar['gerente_id']) && $modalidade_editar['gerente_id'] == $gerente['id']) ? 'selected' : ''; ?>>
                        <?php echo $gerente['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit"><?php echo $editar_id ? 'Atualizar Modalidade' : 'Cadastrar Modalidade'; ?></button>
            <?php if ($editar_id): ?>
                <a href="artes.php" style="display: inline-block; margin-left: 10px; padding: 12px 20px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 8px;">Cancelar</a>
            <?php endif; ?>
        </form>

        <hr>

        <h2>📋 Lista de Modalidades Ativas</h2>
        <?php if (!empty($modalidades)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Gerente</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modalidades as $modalidade): ?>
                        <tr>
                            <td><?php echo $modalidade['nome']; ?></td>
                            <td><?php echo $modalidade['descricao']; ?></td>
                            <td><?php echo $modalidade['nome_gerente']; ?></td>
                            <td>
                                <a href="artes.php?editar=<?php echo $modalidade['id']; ?>" style="color: var(--color-primary); margin-right: 10px;">Editar</a>
                                <a href="artes.php?excluir=<?php echo $modalidade['id']; ?>" 
                                   onclick="return confirm('Tem certeza que deseja excluir a modalidade <?php echo htmlspecialchars($modalidade['nome']); ?>?');"
                                   style="color: #dc2626;">Excluir</a>
                            </td>
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

