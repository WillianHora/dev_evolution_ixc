<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php?erro=2");
    exit;
}

$nome_usuario = $_SESSION['usuario_nome'] ?? $_SESSION['usuario_email'] ?? 'Usuário';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="style/adm.css">
</head>

<body>
    <div class="form-box">

       <div class="admin-header">
            <span class="welcome-msg">
                Bem-vindo(a), <span class="user-name"><?php echo htmlspecialchars($nome_usuario); ?></span>!
            </span>
            <a href="logout.php" class="logout-link">Sair</a>
        </div>

        <h2>Painel Administrativo</h2>
        <p>Selecione uma das opções abaixo para gerenciar.</p>

        <a href="cadastrar_produto.php" class="btn">
            Cadastrar Novo Produto
        </a>

        <a href="listar_produtos.php" class="btn btn-secondary">
            Ver Produtos Cadastrados
        </a>
        <a href="minhas_vendas.php" class="btn btn-thrid">
           Minhas vendas
        </a>

    </div>

</body>

</html>