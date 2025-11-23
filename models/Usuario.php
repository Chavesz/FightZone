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

    // Verifica login e retorna dados do usuário
    public function logar($email, $senha) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $email = htmlspecialchars(strip_tags($email));

        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $senha_hash = $row['senha'];

            // Verifica se a senha está correta
            if (password_verify($senha, $senha_hash)) {
                return $row; // Retorna todos os dados do usuário
            }
        }
        return false; // Login falhou
    }

    // Lista usuários por tipo
    public function listarPorTipo($tipo) {
        $query = "SELECT id, nome FROM " . $this->table_name . " WHERE tipo = :tipo ORDER BY nome";
        $stmt = $this->conn->prepare($query);
        $tipo = htmlspecialchars(strip_tags($tipo));
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
        
        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }
}

