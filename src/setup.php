<?php

require_once 'Database.php';

$db = new Database();

$sql_usuario = "CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY ,
    nome TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    senha TEXT NOT NULL 
)";


$sql_produtos = "CREATE TABLE IF NOT EXISTS produtos (
    id INTEGER PRIMARY KEY ,
    nome TEXT NOT NULL,
    descricao TEXT UNIQUE NOT NULL,
    quantidade TEXT NOT NULL ,
    valor DECIMAL NOT NULL,
    id_usuario INTEGER NOT NULL
)";
    
    
$db->exec($sql_usuario);
echo "Tabela 'Usuarios' criada/verificada com sucesso!\n";
$db->exec($sql_produtos);
echo "Tabela 'Produtos' criada/verificada com sucesso!\n";
