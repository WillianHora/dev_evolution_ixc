<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php?erro=2");
    exit;
}

require_once '../src/Database.php';
$nome_usuario = $_SESSION['usuario_nome'] ?? $_SESSION['usuario_email'] ?? 'Usuário';
$id_usuario_logado = $_SESSION['user_id'];


function buscarProdutosPorUsuario($db, $id_usuario) {
    $sql = "SELECT id, nome, descricao, quantidade, valor FROM produtos WHERE id_usuario = :id_usuario";

    $stmt = $db->prepare($sql);
    $stmt->execute([':id_usuario' => $id_usuario]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$db = new Database();
$produtos_do_usuario = buscarProdutosPorUsuario($db, $id_usuario_logado);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Produtos</title>
    <link rel="stylesheet" href="/style/produtos.css"> 
</head>
<body>

    <div class="container">
        <div class="admin-header">
            <span class="welcome-msg">
                Usuário(a), <span class="user-name"><?php echo htmlspecialchars($nome_usuario); ?></span>!
            </span>
            <a href="logout.php" class="logout-link">Sair</a>
        </div>

        <div class="painel-header">
            <h2>Meus Produtos</h2>
            <a href="cadastrar_produto.php" class="btn btn-primary">Cadastrar Novo Produto</a>
        </div>

        <div class="tabela-container">
            <?php if (empty($produtos_do_usuario)): ?>
                <p class="sem-produtos">Você ainda não cadastrou nenhum produto. <a href="cadastrar_produto.php">Clique aqui para começar!</a></p>
            <?php else: ?>
                <table class="tabela-produtos">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                            <th>Valor (R$)</th>
                            <th>Ações</th> </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos_do_usuario as $produto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                                <td><?php echo htmlspecialchars($produto['quantidade']); ?></td>
                                <td>R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></td>
                                <td class="acoes">
                                    <a href="editar.php?id=<?php echo $produto['id']; ?>" class="btn-acao btn-editar">Editar</a>
                                    <a href="excluir.php?id=<?php echo $produto['id']; ?>" class="btn-acao btn-excluir" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
    </div>

</body>
</html>