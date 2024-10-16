<?php
require_once '../controllers/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($auth->login($_POST['email'], $_POST['password'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciais inválidas.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .login-container h4 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
        }

        .form-group label {
            font-size: 14px;
            color: #555;
        }

        .form-control {
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .card-footer a {
            font-size: 14px;
            text-decoration: none;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 16px;
            text-decoration: none;
            color: #007bff;
        }

        .back-btn:hover {
            color: #0056b3;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
        }

        footer a {
            text-decoration: none;
            color: #007bff;
        }

        footer a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Botão "Voltar" -->
    <a href="../index.php" class="back-btn"><i class="bi bi-arrow-left"></i> Voltar</a>

    <div class="login-container">
        <h4 class="text-center">Acesse sua conta</h4>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" class="form-control" name="password" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Entrar</button>
        </form>

        <div class="card-footer text-center mt-4">
            <a href="register.php">Não tem uma conta? Cadastre-se aqui!</a>
        </div>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
