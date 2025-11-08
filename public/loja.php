<?php

session_start(); 

require_once '../src/Database.php';

function buscarTodosProdutosDisponiveis($db) {
    // A regra de negócio que você pediu:
    $sql = "SELECT id, nome, descricao, quantidade, valor 
            FROM produtos 
            WHERE quantidade > 0"; // Só mostra se tiver estoque
    $stmt = $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$db = new Database();
$produtos_disponiveis = buscarTodosProdutosDisponiveis($db);

$is_admin_logado = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja - Comprar Produtos</title>
    <link rel="stylesheet" href="/style/loja.css"> 
</head>
<body>

    <div class="container">
        <div class="admin-header">
            <span class="welcome-msg">
                Seja bem-vindo à nossa Loja!
            </span>
            <div>
                <?php if ($is_admin_logado): ?>
                    <a href="adm.php" class="btn-link">Meu Painel (Vendedor)</a>
                <?php else: ?>
                    <a href="index.php" class="btn-link">Login de Vendedor</a>
                <?php endif; ?>
            </div>
        </div>

        <h2>Produtos Disponíveis</h2>

        <?php if(isset($_GET['erro'])): ?>
            <p class="mensagem-erro">Erro: <?php echo htmlspecialchars($_GET['erro']); ?></p>
        <?php endif; ?>
        <?php if(isset($_GET['sucesso'])): ?>
            <p class="mensagem-sucesso">Compra realizada com sucesso!</p>
        <?php endif; ?>

        <div class="product-grid">
            
            <?php if (empty($produtos_disponiveis)): ?>
                <p class="sem-produtos">Nenhum produto disponível no momento.</p>
            <?php else: ?>
                <?php foreach ($produtos_disponiveis as $produto): ?>
                    
                    <div class="product-card">
                        <h3><?php echo htmlspecialchars($produto['nome']); ?></h3>
                        <p class="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></p>
                        <p class="preco">R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></p>
                        <p class="estoque">Estoque: <?php echo $produto['quantidade']; ?> un.</p>

                        <form action="registrar_venda.php" method="POST">
                            <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
                            <input type="hidden" name="valor_produto" value="<?php echo $produto['valor']; ?>">
                            
                            <div class="form-group-compra">
                                <label for="quantidade-<?php echo $produto['id']; ?>">Quantidade:</label>
                                <input type="number" 
                                       name="quantidade" 
                                       id="quantidade-<?php echo $produto['id']; ?>" 
                                       value="1" 
                                       min="1" 
                                       max="<?php echo $produto['quantidade']; ?>" 
                                       required>
                            </div>
                            
                            <button type="submit" class="btn-comprar">Comprar</button>
                        </form>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>