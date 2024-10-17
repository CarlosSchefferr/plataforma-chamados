<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $codigoValidacao = $_POST['codigo_validacao'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND codigo_validacao = ?");
    $stmt->execute([$email, $codigoValidacao]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET email_verificado = 1 WHERE email = ?");
        $stmt->execute([$email]);

        $success = "E-mail verificado com sucesso!";
        header("Location: login.php?success=" . urlencode($success));
        exit;
    } else {
        $error = "Código de validação inválido!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 50px;
            max-width: 500px;
        }
        .container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-label {
            color: #555;
        }
        .btn-primary {
            width: 100%;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
    <title>Validação de E-mail</title>
</head>
<body>

<div class="container">
    <h2>Validação de E-mail</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="codigo_validacao" class="form-label">Código de Validação</label>
            <input type="text" class="form-control" id="codigo_validacao" name="codigo_validacao" required>
        </div>
        <button type="submit" class="btn btn-primary">Validar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
