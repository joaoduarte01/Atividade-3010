-- Criar banco de dados
CREATE DATABASE kanban_sistema;
USE kanban_sistema;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela de tarefas
CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    descricao TEXT NOT NULL,
    setor VARCHAR(50) NOT NULL,
    prioridade ENUM('baixa', 'media', 'alta') NOT NULL,
    data_cadastro DATETIME NOT NULL,
    status ENUM('a_fazer', 'fazendo', 'pronto') NOT NULL DEFAULT 'a_fazer',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserir alguns dados de exemplo
INSERT INTO usuarios (nome, email) VALUES 
('João Silva', 'joao.silva@empresa.com'),
('Maria Santos', 'maria.santos@empresa.com'),
('Pedro Oliveira', 'pedro.oliveira@empresa.com');

INSERT INTO tarefas (usuario_id, descricao, setor, prioridade, data_cadastro, status) VALUES 
(1, 'Revisar documentação técnica', 'TI', 'alta', NOW(), 'a_fazer'),
(2, 'Atualizar planilha de custos', 'Financeiro', 'media', NOW(), 'fazendo'),
(3, 'Preparar relatório mensal', 'Administrativo', 'baixa', NOW(), 'pronto'),
(1, 'Testar nova funcionalidade', 'TI', 'alta', NOW(), 'a_fazer');