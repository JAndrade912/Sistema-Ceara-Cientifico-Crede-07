CREATE DATABASE SACC;
USE SACC;

-- Tabela de administração
CREATE TABLE Administracao (
    id_admin INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(45) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- Categorias
CREATE TABLE Categoria (
    id_categoria INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL
);

-- Status
CREATE TABLE `Status` (
    id_status INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL
);

-- Contatos
CREATE TABLE Contatos (
    id_contatos INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(45) NOT NULL
);

-- Jurados
CREATE TABLE Jurados (
    id_jurados INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(45) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    id_contatos INT NULL,
    id_categoria INT NULL
);

-- Áreas
CREATE TABLE Areas (
    id_areas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL
);

-- Escolas
CREATE TABLE Escolas (
    id_escolas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL,
    focalizada TINYINT(1) NOT NULL,
    ide VARCHAR(45) NULL,
    municipio VARCHAR(45) NOT NULL
);

-- Trabalhos
CREATE TABLE Trabalhos (
    id_trabalhos INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(45) NOT NULL,
    observacoes VARCHAR(45) NOT NULL,
    id_escolas INT NULL,
    id_jurados INT NULL,
    id_status INT NULL,
    id_areas INT NULL,
    id_categoria INT NULL
);

-- Notas
CREATE TABLE Notas (
    id_notas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    notas DECIMAL(4,2) NOT NULL,
    id_trabalhos INT NULL,
    id_jurados INT NULL,
    id_escolas INT NULL
);

-- Relacionamentos
ALTER TABLE Jurados 
ADD CONSTRAINT fk_jurados_contatos 
FOREIGN KEY (id_contatos) 
REFERENCES Contatos(id_contatos) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

ALTER TABLE Jurados
ADD CONSTRAINT fk_jurados_categoria
FOREIGN KEY (id_categoria)
REFERENCES Categoria(id_categoria)
ON DELETE SET NULL
ON UPDATE CASCADE;

ALTER TABLE Trabalhos
ADD CONSTRAINT fk_trabalhos_escolas
FOREIGN KEY (id_escolas)
REFERENCES Escolas(id_escolas)
ON DELETE SET NULL
ON UPDATE CASCADE;

ALTER TABLE Trabalhos
ADD CONSTRAINT fk_trabalhos_jurados
FOREIGN KEY (id_jurados)
REFERENCES Jurados(id_jurados)
ON DELETE SET NULL
ON UPDATE CASCADE;

ALTER TABLE Trabalhos
ADD CONSTRAINT fk_trabalhos_status
FOREIGN KEY (id_status)
REFERENCES `Status`(id_status)
ON DELETE SET NULL
ON UPDATE CASCADE;

ALTER TABLE Trabalhos
ADD CONSTRAINT fk_trabalhos_area
FOREIGN KEY (id_areas)
REFERENCES Areas(id_areas)
ON DELETE SET NULL
ON UPDATE CASCADE;

ALTER TABLE Trabalhos
ADD CONSTRAINT fk_trabalhos_categoria
FOREIGN KEY (id_categoria)
REFERENCES Categoria(id_categoria)
ON DELETE SET NULL 
ON UPDATE CASCADE;

ALTER TABLE Notas
ADD CONSTRAINT fk_notas_trabalhos
FOREIGN KEY (id_trabalhos)
REFERENCES Trabalhos(id_trabalhos)
ON DELETE SET NULL
ON UPDATE CASCADE;

ALTER TABLE Notas
ADD CONSTRAINT fk_notas_jurados
FOREIGN KEY (id_jurados)
REFERENCES Jurados(id_jurados)
ON DELETE SET NULL 
ON UPDATE CASCADE;

ALTER TABLE Notas
ADD CONSTRAINT fk_notas_escolas
FOREIGN KEY (id_escolas)
REFERENCES Escolas(id_escolas)
ON DELETE SET NULL
ON UPDATE CASCADE;
