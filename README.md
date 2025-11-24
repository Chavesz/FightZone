# FIGHTZONE: Sistema de Gestão de Modalidades

## I. VISÃO GERAL DO PROJETO

Sistema web desenvolvido em **PHP Puro** para gestão de alunos e inscrição em modalidades de artes marciais. O projeto utiliza o padrão MVC (Model, View, Controller).

---

## 🛠️ SETUP LOCAL (PRIMEIROS PASSOS)

Para rodar o projeto, você deve ter o **XAMPP/WAMP (Apache e MySQL)** instalado.

### 1. Preparação dos Arquivos

1.  **Mova** a pasta `FightZone` (este repositório) para dentro do diretório `htdocs` do seu XAMPP.
2.  Inicie os módulos **Apache** e **MySQL**.

### 2. Acesso ao Sistema

O sistema pode ser acessado pelo navegador:
`http://localhost/fightzone/login.php`

---

## 💾 CONFIGURAÇÃO DO BANCO DE DADOS

O banco de dados deve ser configurado localmente.

### 1. Criar e Importar Estrutura

1.  Acesse o **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Crie o banco de dados `fightzone`.
3.  Vá para a aba **SQL** e execute o código contido no arquivo **`database/schema.sql`** para criar todas as tabelas.

### 2. Contas de Teste (Usuários Iniciais)

O sistema exige usuários com diferentes permissões. Insira as seguintes contas (Senha para todas é **`123`**):

* **Nota:** O valor longo (hash) da senha `123` é obrigatório para que o login funcione.

| Tipo | E-mail de Teste | Senha (Hash) |
| :--- | :--- | :--- |
| **Admin** | `admin@fightzone.com` | *[INSERIR HASH AQUI]* |
| **Gerente** | `gerente@fightzone.com` | *[INSERIR HASH AQUI]* |
| **Aluno** | `aluno@fightzone.com` | *[INSERIR HASH AQUI]* |

**(Substitua a linha `[INSERIR HASH AQUI]` pelo hash real que você gerou.)**

---

## 🤝 COLABORAÇÃO

O projeto demonstra a colaboração e divisão de trabalho por responsabilidades:

* **[Kauã da silva chaves pereira]:** Lógica de Back-end (Models, Controllers) e Arquitetura.
* **[Kauã Rodrigues morais]:** Criação das Tabelas SQL e Implementação das Views (Interface).