<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: adm.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style/login.css">
</head>

<body>
    <div class="form-box">
        <h2>Login</h2>
        <?php
        if (isset($_GET['erro'])) {

            if ($_GET['erro'] == 1) {
                echo '<p style="color: red; font-weight: bold; margin-bottom: 15px;">Email ou senha inválidos.</p>';

            } elseif ($_GET['erro'] == 2) {
                echo '<p style="color: #b30000; font-weight: bold; margin-bottom: 15px;">Acesso restrito. Faça login para continuar.</p>';
            }

        }
        ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="login-senha">Senha:</label>
                <input type="password" id="login-senha" name="senha" required>
            </div>
            <button type="submit" class="btn">Logar</button>
        </form>

        <div class="separator"></div>

        <a href="criar.php" class="btn btn-secondary">
            Criar nova conta
        </a>
    </div>

</body>

</html>