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
    descricao TEXT NOT NULL,
    quantidade TEXT NOT NULL ,
    valor DECIMAL NOT NULL,
    id_usuario INTEGER NOT NULL
)";

$sql_vendas = "CREATE TABLE IF NOT EXISTS vendas (
    id INTEGER PRIMARY KEY,
    id_produto INTEGER NOT NULL,
    quantidade INTEGER NOT NULL,
    valor_total REAL NOT NULL,
    data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
)";
    
    
$db->exec($sql_usuario);
echo "Tabela 'Usuarios' criada/verificada com sucesso!\n";
$db->exec($sql_produtos);
echo "Tabela 'Produtos' criada/verificada com sucesso!\n";
$db->exec($sql_vendas);
echo "Tabela 'Vendas' criada/verificada com sucesso!\n";
