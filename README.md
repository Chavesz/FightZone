# FightZone

Sistema web para academias de artes marciais: cadastro de alunos, modalidades, aulas e inscrições, com áreas separadas para administrador, gerente e aluno.

## Funcionalidades

- Login com senha criptografada (`password_hash` / `password_verify`)
- Três perfis com painéis próprios: admin, gerente e aluno
- Cadastro de modalidades, artes marciais e aulas
- Inscrição de alunos em modalidades

## Stack

PHP puro no padrão MVC (Models, Views, Controllers), PDO e MySQL.

## Estrutura

```
config/       conexão com o banco (PDO)
models/       Usuario, Modalidade, Aula, AlunosModalidade
controllers/  regras de negócio de cada entidade
admin/ gerente/ aluno/   painéis por perfil
database/     schema.sql e seed.sql
```

## Como rodar

1. Instale o XAMPP (Apache + MySQL) e coloque esta pasta em `htdocs/fightzone`.
2. No phpMyAdmin, crie o banco `fightzone` e rode `database/schema.sql` e depois `database/seed.sql`.
3. Acesse http://localhost/fightzone/login.php

Contas de teste (senha `123`): `admin@fightzone.com`, `gerente@fightzone.com` e `aluno@fightzone.com`.

## Equipe

Projeto em dupla da faculdade.

- **Kauã Chaves:** back-end (models e controllers) e arquitetura MVC
- **Kauã Rodrigues Morais:** tabelas SQL e views

## O que aprendi

Separar responsabilidades no MVC sem framework, usar PDO com parâmetros para evitar SQL injection e controlar acesso por perfil.
