<?php

require_once __DIR__ . '/../models/Aula.php';

class AulaController {

    private $aulaModel;

    public function __construct() {
        $this->aulaModel = new Aula();
    }

    // Cria uma nova aula no cronograma
    public function criarAula($data) {
        if (empty($data['modalidade_id']) || empty($data['dia_semana']) || empty($data['horario'])) {
            return ['status' => 'error', 'message' => 'Modalidade, dia da semana e horário são obrigatórios.'];
        }

        $this->aulaModel->modalidade_id = $data['modalidade_id'];
        $this->aulaModel->dia_semana = $data['dia_semana'];
        $this->aulaModel->horario = $data['horario'];
        $this->aulaModel->instrutor_id = $data['instrutor_id'] ?? null;

        if ($this->aulaModel->criar()) {
            return ['status' => 'success', 'message' => 'Aula criada com sucesso!'];
        }

        return ['status' => 'error', 'message' => 'Erro ao criar aula.'];
    }

    // Lista todas as aulas cadastradas
    public function listarTodasAulas() {
        $stmt = $this->aulaModel->listar();
        $aulas = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $aulas[] = $row;
        }

        return $aulas;
    }

    // Busca aulas de uma modalidade específica
    public function getAulasPorModalidade($modalidade_id) {
        $stmt = $this->aulaModel->listarPorModalidade($modalidade_id);
        $aulas = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $aulas[] = $row;
        }

        return $aulas;
    }
}

