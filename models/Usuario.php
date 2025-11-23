<?php

require_once __DIR__ . '/../config/db.php';

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public function __construct() {
        
        // Conecta com o banco
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Cadastra um novo usuário
    public function cadastrar($nome, $email, $senha, $tipo = 'aluno') {
        $query = "INSERT INTO " . $this->table_name . " (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
        
        $stmt = $this->conn->prepare($query);

        // Limpa os dados
        $nome = htmlspecialchars(strip_tags($nome));
        $email = htmlspecialchars(strip_tags($email));

        // Criptografa a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":tipo", $tipo);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
}

