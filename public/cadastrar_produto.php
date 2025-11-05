<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php?erro=2");
    exit;
}

$nome_usuario = $_SESSION['usuario_nome'] ?? $_SESSION['usuario_email'] ?? 'Usuário';

require_once '../src/Database.php';

$mensagem_sucesso = "";
$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifique se os campos esperados para o PRODUTO estão definidos
    if (isset($_POST["nome"]) && isset($_POST["descricao"]) && isset($_POST["quantidade"]) && isset($_POST["valor"])) {
        
        $db = new Database();

        $nome = trim($_POST["nome"]); // Trim para remover espaços extras
        $descricao = trim($_POST["descricao"]);
        $quantidade = (int)$_POST["quantidade"]; // Converte para inteiro
        $valor = (float)$_POST["valor"];       // Converte para float (decimal)

        // Validações básicas
        if (empty($nome) || empty($descricao) || $quantidade < 0 || $valor < 0) {
            $mensagem_erro = "Por favor, preencha todos os campos corretamente.";
        } else {
            // SQL para INSERIR UM PRODUTO na tabela 'produtos' (adapte o nome da sua tabela)
            // Assumo que você terá uma tabela 'produtos' com colunas 'nome', 'descricao', 'quantidade', 'valor'
            $sql = "INSERT INTO produtos (nome, descricao, quantidade, valor) VALUES (:nome, :descricao, :quantidade, :valor)";
            $stmt = $db->prepare($sql);

            try {
                $stmt->execute([
                    ':nome' => $nome,
                    ':descricao' => $descricao, 
                    ':quantidade' => $quantidade,
                    ':valor' => $valor
                ]);
                $mensagem_sucesso = "Produto cadastrado com sucesso!";
                // Opcional: Limpar os campos do formulário após o sucesso
                $_POST = array(); 

            } catch (PDOException $e) {
                // Erro de código 23000 ou 19 geralmente indica uma violação de chave única (ex: nome do produto já existe)
                if ($e->getCode() == 23000 || $e->getCode() == 19) {
                    $mensagem_erro = "Erro: Um produto com esse nome já está cadastrado.";
                } else {
                    $mensagem_erro = "Erro ao cadastrar produto: " . $e->getMessage();
                }
            }
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos do produto.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="/style/criar.css"> </head>
<body>
    <div class="admin-header">
        <span class="welcome-msg">
            Bem-vindo(a), <span class="user-name"><?php echo htmlspecialchars($nome_usuario); ?></span>!
        </span>
        <a href="logout.php" class="logout-link">Sair</a>
    </div>

    <div class="form-box">
        <h2>Cadastrar Produto</h2>
        
        <?php if (!empty($mensagem_sucesso)): ?>
            <p class="mensagem-sucesso"><?php echo $mensagem_sucesso; ?></p>
        <?php endif; ?>
        <?php if (!empty($mensagem_erro)): ?>
            <p class="mensagem-erro"><?php echo $mensagem_erro; ?></p>
        <?php endif; ?>

        <form method="POST" action="criar.php">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" min="0" value="<?php echo htmlspecialchars($_POST['quantidade'] ?? ''); ?>" required>
            </div>
             <div class="form-group">
                <label for="valor">Valor (R$):</label>
                <input type="number" id="valor" name="valor" step="0.01" min="0.01" value="<?php echo htmlspecialchars($_POST['valor'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn">Cadastrar Produto</button>
        </form>

        <div class="separator"></div>

        <a href="adm.php" class="btn btn-secondary">
            Voltar ao Painel
        </a>

    </div>

</body>
</html>