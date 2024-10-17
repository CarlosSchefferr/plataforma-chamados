
USE plataforma_chamados;

-- Tabela de usuários
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20),
    senha VARCHAR(255) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    validado BOOLEAN DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    codigo_validacao VARCHAR(6),
    email_verificado TINYINT DEFAULT 0
);

-- Tabela de chamados
CREATE TABLE chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    descricao TEXT NOT NULL,
    tipo_incidente VARCHAR(100) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'aberto',
    anexos LONGTEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES users(id)
);

-- Tabela de contatos
CREATE TABLE contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chamado_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    observacao TEXT,
    FOREIGN KEY (chamado_id) REFERENCES chamados(id)
);

-- Tabela de histórico de chamados
CREATE TABLE historico_chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chamado_id INT NOT NULL,
    descricao TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chamado_id) REFERENCES chamados(id)
);

-- Tabela de anexos
CREATE TABLE anexos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chamado_id INT NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chamado_id) REFERENCES chamados(id) ON DELETE CASCADE
);


