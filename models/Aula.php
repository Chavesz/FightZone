<?php

require_once __DIR__ . '/../config/db.php';

class Aula {

    private $conn;
    private $table_name = "aulas";

    public $id;
    public $modalidade_id;
    public $dia_semana;
    public $horario;
    public $instrutor_id;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Cria uma nova aula no cronograma
    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " (modalidade_id, dia_semana, horario, instrutor_id) VALUES (:modalidade_id, :dia_semana, :horario, :instrutor_id)";
        $stmt = $this->conn->prepare($query);

        $this->dia_semana = htmlspecialchars(strip_tags($this->dia_semana));
        
        $stmt->bindParam(":modalidade_id", $this->modalidade_id);
        $stmt->bindParam(":dia_semana", $this->dia_semana);
        $stmt->bindParam(":horario", $this->horario);
        $stmt->bindParam(":instrutor_id", $this->instrutor_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lista todas as aulas cadastradas
    public function listar() {
        $query = "SELECT a.id, a.dia_semana, a.horario, m.nome as modalidade_nome, u.nome as instrutor_nome
                  FROM " . $this->table_name . " a
                  LEFT JOIN modalidades m ON a.modalidade_id = m.id
                  LEFT JOIN usuarios u ON a.instrutor_id = u.id
                  ORDER BY a.dia_semana, a.horario";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lista aulas por modalidade
    public function listarPorModalidade($modalidade_id) {
        $query = "SELECT a.id, a.dia_semana, a.horario, u.nome as instrutor_nome
                  FROM " . $this->table_name . " a
                  LEFT JOIN usuarios u ON a.instrutor_id = u.id
                  WHERE a.modalidade_id = :modalidade_id
                  ORDER BY a.dia_semana, a.horario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":modalidade_id", $modalidade_id);
        $stmt->execute();
        return $stmt;
    }
}

