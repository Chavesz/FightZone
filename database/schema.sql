CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('aluno', 'gerente', 'admin') NOT NULL DEFAULT 'aluno'
);

CREATE TABLE modalidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    descricao TEXT,
    gerente_id INT,
    FOREIGN KEY (gerente_id) REFERENCES usuarios(id)
);

CREATE TABLE alunos_modalidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    modalidade_id INT NOT NULL,
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id),
    FOREIGN KEY (modalidade_id) REFERENCES modalidades(id)
);

