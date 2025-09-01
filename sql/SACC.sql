CREATE DATABASE SACC;
USE SACC;

CREATE TABLE Administracao(
    id_admin INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(45) NOT NULL,
    senha VARCHAR(6) NOT NULL
);
CREATE TABLE Categoria(
	id_categoria INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL
);
CREATE TABLE `Status` (
	id_status INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL
);
CREATE TABLE Contatos(
    id_contatos INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    telefone INT(11) NOT NULL,
    email VARCHAR(45) NOT NULL
);
CREATE TABLE Jurados (
	id_jurados INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usu_jurado VARCHAR(45) NOT NULL,
    pass_jurado VARCHAR(6) NOT NULL,
    cpf INT(11) NOT NULL,
    id_contato INT,
    id_categoria INT
);
CREATE TABLE Areas (
	id_areas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL
);
CREATE TABLE Escolas(
 	id_escolas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(45) NOT NULL,
    focalizada TINYINT(1) NOT NULL,
    ide VARCHAR(45) NOT NULL,
    municipio VARCHAR(45) NOT NULL
);
CREATE TABLE Trabalhos(
 	id_trabalhos INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(45) NOT NULL,
    observacoes VARCHAR(45) NOT NULL,
    id_escolas INT,
    id_jurados INT,
    id_status INT,
    id_areas INT,
    id_categoria INT
);
CREATE TABLE Notas(
	id_notas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    notas FLOAT(4) NOT NULL,
    id_trabalhos INT,
    id_jurados INT,
    id_escolas INT
);

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