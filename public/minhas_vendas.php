<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php?erro=2");
    exit;
}
require_once '../src/Database.php';
$nome_usuario = $_SESSION['usuario_nome'] ?? $_SESSION['usuario_email'] ?? 'Usuário';
$id_usuario_logado = $_SESSION['user_id'];

function buscarMinhasVendas($db, $id_usuario_logado) {
    
    $sql = "SELECT 
                P.nome AS nome_produto,
                V.quantidade AS quantidade_vendida,
                V.valor_total,
                V.data_venda
            FROM vendas AS V
            JOIN produtos AS P ON V.id_produto = P.id
            WHERE 
                P.id_usuario = :id_usuario_logado
            ORDER BY 
                V.data_venda DESC"; // Mais novas primeiro

    $stmt = $db->prepare($sql);
    $stmt->execute([':id_usuario_logado' => $id_usuario_logado]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$db = new Database();
$minhas_vendas = buscarMinhasVendas($db, $id_usuario_logado);

$valor_total_arrecadado = 0;
foreach ($minhas_vendas as $venda) {
    $valor_total_arrecadado += $venda['valor_total'];
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vendas</title>
    <link rel="stylesheet" href="/style/produtos.css"> 
</head>
<body>

    <div class="container">
        <div class="admin-header">
            <span class="welcome-msg">
                Bem-vindo(a), <span class="user-name"><?php echo htmlspecialchars($nome_usuario); ?></span>!
            </span>
            <div>
                 <a href="adm.php" class="btn-link-nav">Meus Produtos</a>
                 <a href="loja.php" class="btn-link-nav btn-loja">Ver Loja</a>
                 <a href="logout.php" class="logout-link">Sair</a>
            </div>
        </div>

        <div class="painel-header">
            <h2>Histórico de Vendas</h2>
        </div>

        <div class="resumo-vendas">
            <h3>Resumo Total</h3>
            <p>Total Arrecadado: <span>R$ <?php echo number_format($valor_total_arrecadado, 2, ',', '.'); ?></span></p>
            <p>Vendas Realizadas: <span><?php echo count($minhas_vendas); ?></span></p>
        </div>

        <div class="tabela-container">
            <?php if (empty($minhas_vendas)): ?>
                
                <p class="sem-produtos">Você ainda não realizou nenhuma venda.</p>
                
            <?php else: ?>
                
                <table class="tabela-produtos">
                    <thead>
                        <tr>
                            <th>Data da Venda</th>
                            <th>Produto Vendido</th>
                            <th>Quantidade</th>
                            <th>Valor Total (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($minhas_vendas as $venda): ?>
                            <tr>
                                <td><?php echo (new DateTime($venda['data_venda']))->format('d/m/Y H:i'); ?></td>
                                
                                <td><?php echo htmlspecialchars($venda['nome_produto']); ?></td>
                                <td><?php echo htmlspecialchars($venda['quantidade_vendida']); ?></td>
                                <td>R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php endif; ?>
        </div>
        
    </div>

</body>
</html>