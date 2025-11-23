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
}

