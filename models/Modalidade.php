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

    // Lista modalidades por gerente
    public function listarPorGerente($gerente_id) {
        $query = "SELECT m.id, m.nome, m.descricao, u.nome as nome_gerente
                  FROM " . $this->table_name . " m
                  LEFT JOIN usuarios u ON m.gerente_id = u.id
                  WHERE m.gerente_id = :gerente_id
                  ORDER BY m.nome";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":gerente_id", $gerente_id);
        $stmt->execute();
        return $stmt;
    }

    // Busca uma modalidade por ID
    public function buscarPorId($id) {
        $query = "SELECT m.id, m.nome, m.descricao, m.gerente_id, u.nome as nome_gerente
                  FROM " . $this->table_name . " m
                  LEFT JOIN usuarios u ON m.gerente_id = u.id
                  WHERE m.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualiza uma modalidade
    public function atualizar() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nome = :nome, descricao = :descricao, gerente_id = :gerente_id 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":gerente_id", $this->gerente_id);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Exclui uma modalidade
    public function excluir() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
