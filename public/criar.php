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
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .form-box { background: #fff; padding: 30px 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 320px; text-align: center; }
        .form-box h2 { margin-top: 0; margin-bottom: 25px; color: #333; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; }
        .btn { background-color: #28a745; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; transition: background-color 0.3s; }
        .btn:hover { background-color: #218838; }
        .link-login { display: block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .link-login:hover { text-decoration: underline; }
        /* Estilos para as mensagens de feedback */
        .mensagem-sucesso { color: green; font-weight: bold; margin-top: 15px; }
        .mensagem-erro { color: red; font-weight: bold; margin-top: 15px; }
    </style>
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