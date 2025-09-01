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
	id_jurado INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usu_jurado VARCHAR(45) NOT NULL,
    pass_jurado VARCHAR(6) NOT NULL,
    cpf INT(11) NOT NULL,
    id_contato INT,
    id_categoria INT
);
CREATE TABLE Area (
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
 	id_trabalho INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(45) NOT NULL,
    observacoes VARCHAR(45) NOT NULL,
    id_escola INT,
    id_jurado INT,
    id_status INT,
    id_area INT,
    id_categoria INT
);
CREATE TABLE Notas(
	id_notas INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    notas FLOAT(4) NOT NULL,
    id_trabalho INT,
    id_jurado INT,
    id_escola INT
);


