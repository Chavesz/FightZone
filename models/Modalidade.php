<?php

require_once __DIR__ . '/../config/db.php';

class Modalidade {

    private $conn;
    private $table_name = "modalidades";

    public $id;
    public $nome;
    public $descricao;
    public $gerente_id;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Cria uma nova modalidade no banco de dados
    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao, gerente_id) VALUES (:nome, :descricao, :gerente_id)";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":gerente_id", $this->gerente_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lista todas as modalidades cadastradas
    public function listar() {
        $query = "SELECT m.id, m.nome, m.descricao, u.nome as nome_gerente
                  FROM " . $this->table_name . " m
                  LEFT JOIN usuarios u ON m.gerente_id = u.id
                  ORDER BY m.nome";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
