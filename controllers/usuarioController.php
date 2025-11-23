<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function registrar($data) {
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            return ['status' => 'error', 'message' => 'Todos os campos são obrigatórios.'];
        }

        $cadastro_sucesso = $this->usuarioModel->cadastrar(
            $data['nome'],
            $data['email'],
            $data['senha']
        );

        if ($cadastro_sucesso) {
            return ['status' => 'success', 'message' => 'Cadastro realizado com sucesso!'];
        } else {
            return ['status' => 'error', 'message' => 'Erro ao salvar no banco de dados.'];
        }
    }

    public function logar($data) {
        if (empty($data['email']) || empty($data['senha'])) {
            return ['status' => 'error', 'message' => 'Email e senha são obrigatórios.'];
        }

        $usuario = $this->usuarioModel->logar($data['email'], $data['senha']);

        if ($usuario) {
            $_SESSION['usuario_logado'] = true;
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_tipo'] = $usuario['tipo'];
            
            return ['status' => 'success', 'message' => 'Login realizado com sucesso!', 'tipo' => $usuario['tipo']];
        } else {
            return ['status' => 'error', 'message' => 'Email ou senha incorretos.'];
        }
    }

    // Busca alunos inscritos em uma modalidade
    public function obterAlunosPorModalidade($modalidade_id) {
        $stmt = $this->usuarioModel->listarAlunosInscritos($modalidade_id);
        $alunos = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $alunos[] = $row;
        }
        return $alunos;
    }

    // Lista usuários por tipo
    public function listarPorTipo($tipo) {
        return $this->usuarioModel->listarPorTipo($tipo);
    }
}

