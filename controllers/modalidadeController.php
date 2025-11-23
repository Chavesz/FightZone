<?php

require_once __DIR__ . '/../models/Modalidade.php';

class ModalidadeController {

    private $modalidadeModel;

    public function __construct() {
        $this->modalidadeModel = new Modalidade();
    }

    // Cria uma nova modalidade
    public function criarModalidade($data) {
        if (empty($data['nome']) || empty($data['gerente_id'])) {
            return ['status' => 'error', 'message' => 'Nome e Gerente são obrigatórios.'];
        }

        $this->modalidadeModel->nome = $data['nome'];
        $this->modalidadeModel->descricao = $data['descricao'] ?? ''; 
        $this->modalidadeModel->gerente_id = $data['gerente_id'];

        if ($this->modalidadeModel->criar()) {
            return ['status' => 'success', 'message' => 'Modalidade criada com sucesso!'];
        }

        return ['status' => 'error', 'message' => 'Erro ao criar modalidade.'];
    }

    // Lista todas as modalidades cadastradas
    public function listarModalidades() {
        $stmt = $this->modalidadeModel->listar();
        $modalidades = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $modalidades[] = $row;
        }

        return $modalidades;
    }

    // Busca modalidades de um gerente específico
    public function obterModalidadesDoGerente($gerente_id) {
        $stmt = $this->modalidadeModel->listarPorGerente($gerente_id);
        $modalidades = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $modalidades[] = $row;
        }

        return $modalidades;
    }
}

