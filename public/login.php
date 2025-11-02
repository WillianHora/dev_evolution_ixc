<?php
session_start();

require_once '../src/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['senha'])) {

    $db = new Database();

    $email = $_POST['email'];
    $senha_plana = $_POST['senha']; 

    $sql = "SELECT id, email, senha FROM usuarios WHERE email = :email";
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $email]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha_plana, $user['senha'])) {
        
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['logged_in'] = true;

   
        header("Location: adm.php");
        exit;

    } else {
        header("Location: index.php?erro=1");
        exit;
    }

} else {
    header("Location: index.php");
    exit;
}
