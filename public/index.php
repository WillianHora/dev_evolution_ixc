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
    <style>
        body { font-family: Arial, sans-serif; background-color: #dbdbdbff; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .form-box { background: #fff; padding: 30px 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 320px; text-align: center; }
        .form-box h2 { margin-top: 0; margin-bottom: 25px; color: #333; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .form-group input { width: 100%; padding: 12px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; }
        .btn { background-color: #007bff; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; transition: background-color 0.3s; }
        .btn:hover { background-color: #0056b3; }
        .separator { border-bottom: 1px solid #ddd; margin: 25px 0; }
        .btn-secondary { background-color: #28a745; text-decoration: none; display: inline-block; padding: 12px 2px; font-size: 16px; color: white; }
        .btn-secondary:hover { background-color: #218838; }
    </style>
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