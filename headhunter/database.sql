CREATE DATABASE IF NOT EXISTS headhunter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE headhunter;
CREATE TABLE usuarios(id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(120) NOT NULL, email VARCHAR(160) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL, tipo ENUM('candidato','empresa') NOT NULL, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE candidatos(usuario_id INT PRIMARY KEY, cidade VARCHAR(80), estado CHAR(2), telefone VARCHAR(25), linkedin VARCHAR(200),
  escolaridade VARCHAR(40), area VARCHAR(40), resumo TEXT, habilidades TEXT, idiomas VARCHAR(160), disponibilidade VARCHAR(40),
  pretensao DECIMAL(10,2) NULL, perfil_publico TINYINT(1) DEFAULT 1,
  FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE experiencias(id INT AUTO_INCREMENT PRIMARY KEY, usuario_id INT NOT NULL, cargo VARCHAR(100), empresa VARCHAR(100),
  periodo VARCHAR(60), descricao TEXT, FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE empresas(usuario_id INT PRIMARY KEY, nome_fantasia VARCHAR(120), setor VARCHAR(80), cidade VARCHAR(80), sobre TEXT,
  FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE vagas(id INT AUTO_INCREMENT PRIMARY KEY, empresa_id INT NOT NULL, titulo VARCHAR(140) NOT NULL, descricao TEXT, requisitos TEXT,
  area VARCHAR(40), cidade VARCHAR(80), modalidade ENUM('presencial','hibrido','remoto') DEFAULT 'presencial',
  nivel ENUM('aprendiz','estagio','junior','pleno') DEFAULT 'junior', salario DECIMAL(10,2) NULL, ativa TINYINT(1) DEFAULT 1,
  criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(empresa_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE candidaturas(id INT AUTO_INCREMENT PRIMARY KEY, vaga_id INT NOT NULL, candidato_id INT NOT NULL,
  status ENUM('recebida','em_analise','entrevista','aprovada','recusada') DEFAULT 'recebida', criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(vaga_id,candidato_id), FOREIGN KEY(vaga_id) REFERENCES vagas(id) ON DELETE CASCADE,
  FOREIGN KEY(candidato_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE entrevistas(id INT AUTO_INCREMENT PRIMARY KEY, candidato_id INT NOT NULL, area VARCHAR(40), pergunta VARCHAR(300),
  resposta TEXT, nota TINYINT UNSIGNED, feedback JSON, compartilhada TINYINT(1) DEFAULT 0, criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(candidato_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE INDEX idx_vagas ON vagas(area,cidade,ativa);
CREATE INDEX idx_cand ON candidatos(area,estado,escolaridade);
