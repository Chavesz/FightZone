<?php

require_once __DIR__ . '/../models/AlunosModalidade.php';
require_once __DIR__ . '/../models/Modalidade.php';

class AlunosModalidadeController {

    private $inscricaoModel;
    private $modalidadeModel;

    public function __construct() {
        $this->inscricaoModel = new AlunosModalidade();
        $this->modalidadeModel = new Modalidade();
    }

    // Processa a inscrição do aluno em uma modalidade
    public function processarInscricao($aluno_id, $modalidade_id) {
        $this->inscricaoModel->aluno_id = $aluno_id;
        $this->inscricaoModel->modalidade_id = $modalidade_id;

        $resultado = $this->inscricaoModel->inscrever();

        if ($resultado === true) {
            return ['status' => 'success', 'message' => 'Inscrição realizada com sucesso!'];
        } elseif ($resultado === "DUPLICATE") {
            return ['status' => 'info', 'message' => 'Você já está inscrito nesta modalidade.'];
        }
        return ['status' => 'error', 'message' => 'Erro ao processar a inscrição.'];
    }

    // Lista todas as modalidades disponíveis para o aluno escolher
    public function listarModalidadesDisponiveis() {
        $stmt = $this->modalidadeModel->listar();
        $modalidades = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $modalidades[] = $row;
        }

        return $modalidades;
    }

    // Lista as modalidades que o aluno está inscrito
    public function obterMinhasInscricoes($aluno_id) {
        $stmt = $this->inscricaoModel->listarInscricoes($aluno_id);
        $inscricoes = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $inscricoes[] = $row;
        }

        return $inscricoes;
    }
}

