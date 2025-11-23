<?php

require_once __DIR__ . '/../config/db.php';

class AlunosModalidade {

    private $conn;
    private $table_name = "alunos_modalidade";

    public $aluno_id;
    public $modalidade_id;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Inscreve o aluno em uma modalidade
    public function inscrever() {
        // Verifica se o aluno já está inscrito
        $check_query = "SELECT id FROM " . $this->table_name . " WHERE aluno_id = :aluno_id AND modalidade_id = :modalidade_id LIMIT 1";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":aluno_id", $this->aluno_id);
        $check_stmt->bindParam(":modalidade_id", $this->modalidade_id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            return "DUPLICATE";
        }

        // Insere a nova inscrição
        $query = "INSERT INTO " . $this->table_name . " (aluno_id, modalidade_id) VALUES (:aluno_id, :modalidade_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":aluno_id", $this->aluno_id);
        $stmt->bindParam(":modalidade_id", $this->modalidade_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lista as modalidades que o aluno está inscrito
    public function listarInscricoes($aluno_id) {
        $query = "SELECT m.nome, m.descricao, m.id as modalidade_id, u.nome as nome_gerente
                  FROM " . $this->table_name . " am
                  JOIN modalidades m ON am.modalidade_id = m.id
                  LEFT JOIN usuarios u ON m.gerente_id = u.id
                  WHERE am.aluno_id = :aluno_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":aluno_id", $aluno_id);
        $stmt->execute();
        return $stmt;
    }
}

