<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function registrar($data) {
        // Valida se os campos estão preenchidos
        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            return ['status' => 'error', 'message' => 'Todos os campos são obrigatórios.'];
        }

        // Chama o model para salvar no banco
        $cadastro_sucesso = $this->usuarioModel->cadastrar(
            $data['nome'],
            $data['email'],
            $data['senha']
        );

        // Retorna o resultado
        if ($cadastro_sucesso) {
            return ['status' => 'success', 'message' => 'Cadastro realizado com sucesso!'];
        } else {
            return ['status' => 'error', 'message' => 'Erro ao salvar no banco de dados.'];
        }
    }

    public function logar($data) {
        // Valida se os campos estão preenchidos
        if (empty($data['email']) || empty($data['senha'])) {
            return ['status' => 'error', 'message' => 'Email e senha são obrigatórios.'];
        }

        // Chama a função logar do Model
        $usuario = $this->usuarioModel->logar($data['email'], $data['senha']);

        // Verifica o resultado
        if ($usuario) {
            // Inicia a sessão com os dados do usuário
            $_SESSION['usuario_logado'] = true;
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome'];
            $_SESSION['user_tipo'] = $usuario['tipo'];
            
            return ['status' => 'success', 'message' => 'Login realizado com sucesso!', 'tipo' => $usuario['tipo']];
        } else {
            return ['status' => 'error', 'message' => 'Email ou senha incorretos.'];
        }
    }

    // Lista usuários por tipo
    public function listarPorTipo($tipo) {
        return $this->usuarioModel->listarPorTipo($tipo);
    }
}

