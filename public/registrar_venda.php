<?php
require_once '../src/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_produto = (int)$_POST['id_produto'];
    $quantidade_comprada = (int)$_POST['quantidade'];
    $valor_unitario = (float)$_POST['valor_produto'];
    
    $valor_total = $valor_unitario * $quantidade_comprada;

    if ($quantidade_comprada <= 0 || $id_produto <= 0) {
        header("Location: loja.php?erro=dados_invalidos");
        exit;
    }

    $db = new Database();
    
    try {
        $db->beginTransaction();

    
        $sql_check = "SELECT quantidade FROM produtos WHERE id = :id_produto";
        $stmt_check = $db->prepare($sql_check);
        $stmt_check->execute([':id_produto' => $id_produto]);
        $produto = $stmt_check->fetch(PDO::FETCH_ASSOC);
        $estoque_atual = (int)$produto['quantidade'];

        if ($estoque_atual < $quantidade_comprada) {
            $db->rollBack();
            header("Location: loja.php?erro=estoque_insuficiente");
            exit;
        }

        $sql_venda = "INSERT INTO vendas (id_produto, quantidade, valor_total) 
                      VALUES (:id_produto, :quantidade, :valor_total)";
        $stmt_venda = $db->prepare($sql_venda);
        $stmt_venda->execute([
            ':id_produto' => $id_produto,
            ':quantidade' => $quantidade_comprada,
            ':valor_total' => $valor_total
        ]);

        $novo_estoque = $estoque_atual - $quantidade_comprada;
        $sql_update = "UPDATE produtos SET quantidade = :novo_estoque WHERE id = :id_produto";
        $stmt_update = $db->prepare($sql_update);
        $stmt_update->execute([
            ':novo_estoque' => $novo_estoque,
            ':id_produto' => $id_produto
        ]);

        $db->commit();
        
        header("Location: loja.php?sucesso=1");
        exit;


    } catch (PDOException $e) {
        $db->rollBack();
        header("Location: loja.php?erro=" . $e->getMessage());
        exit;
    }

} else {
    header("Location: loja.php");
    exit;
}
