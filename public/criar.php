<?php

require_once '../src/Database.php'; 

$mensagem_sucesso = "";
$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["senha"])) {
        
        $db = new Database();

        $nome = $_POST["nome"];
        $email = filter_var(strtolower($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $senha_plana = $_POST["senha"];

        $senha_hash = password_hash($senha_plana, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $db->prepare($sql);

        try {
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email, 
                ':senha' => $senha_hash 
            ]);
            $mensagem_sucesso = "Conta criada com sucesso!";

        } catch (PDOException $e) {
            if ($e->getCode() == 23000 || $e->getCode() == 19) {
                $mensagem_erro = "Erro: Este e-mail já está cadastrado.";
            } else {
                $mensagem_erro = "Erro ao criar conta: " . $e->getMessage();
            }
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crie seu login</title>
    <link rel="stylesheet" href="/style/criar.css">
</head>
<body>

    <div class="form-box">
        <h2>Criar Conta</h2>
        
        <form method="POST" action="criar.php">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn">Cadastrar</button>
        </form>

        <?php if (!empty($mensagem_sucesso)): ?>
            <p class="mensagem-sucesso"><?php echo $mensagem_sucesso; ?></p>
        <?php endif; ?>
        <?php if (!empty($mensagem_erro)): ?>
            <p class="mensagem-erro"><?php echo $mensagem_erro; ?></p>
        <?php endif; ?>

        <a href="index.php" class="link-login">Já tem uma conta? Fazer login</a>
    </div>

</body>
</html>