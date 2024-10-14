<?php
require_once '../controllers/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->register($_POST);
    if ($result) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao realizar cadastro.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Cadastro</title>
</head>
<body>
    <form method="POST">
        <label for="name">Nome Completo:</label>
        <input type="text" name="name" required>

        <label for="birthdate">Data de Nascimento:</label>
        <input type="date" name="birthdate" required>

        <label for="email">E-mail:</label>
        <input type="email" name="email" required>
        
        <label for="phone">Telefone:</label>
        <input type="text" name="phone" required>
        
        <label for="whatsapp">WhatsApp:</label>
        <input type="text" name="whatsapp" required>
        
        <label for="password">Senha:</label>
        <input type="password" name="password" required>
        
        <label for="city">Cidade:</label>
        <input type="text" name="city" required>
        
        <label for="state">Estado:</label>
        <input type="text" name="state" required>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
