<?php
require_once '../controllers/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['data_nascimento'])) {
        $data_nascimento = new DateTime($_POST['data_nascimento']);
        $hoje = new DateTime();
        $idade = $hoje->diff($data_nascimento)->y;

        if ($idade < 18) {
            $error = "Você deve ter pelo menos 18 anos para se cadastrar.";
        } else {
            $result = $auth->register($_POST);

            if ($result) {
                $success = "Usuário cadastrado com sucesso!";
                header("Location: login.php?success=" . urlencode($success));
                exit;
            } else {
                $error = "E-mail já está cadastrado!";
            }
        }
    } else {
        $error = "Por favor, insira sua data de nascimento.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Cadastro</title>
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
            max-width: 600px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .login-container h4 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #333;
            text-align: center;
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

        .alert {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .card-footer a {
            font-size: 14px;
            text-decoration: none;
        }

        .card-footer a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>

    <a href="../index.php" class="back-btn"><i class="bi bi-arrow-left"></i> Voltar</a>

    <div class="login-container">
        <h4>Cadastro</h4>

        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="row mb-3">
                <div class="col">
                    <div class="form-group">
                        <label for="nome_completo">Nome Completo</label>
                        <input type="text" class="form-control" name="nome_completo" placeholder="Digite seu nome completo" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" class="form-control" name="data_nascimento" required>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" name="telefone" placeholder="Digite seu telefone" required>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-group">
                        <label for="whatsapp">WhatsApp</label>
                        <input type="text" class="form-control" name="whatsapp" placeholder="Digite seu WhatsApp">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" class="form-control" name="cidade" placeholder="Digite sua cidade" required>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" name="estado" placeholder="Digite seu estado" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" class="form-control" name="senha" placeholder="Digite sua senha" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Cadastrar</button>
        </form>

        <div class="card-footer text-center mt-4">
            <a href="login.php">Já possui uma conta? Faça login aqui!</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
