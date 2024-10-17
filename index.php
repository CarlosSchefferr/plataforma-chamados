<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Chamados de TI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1dJ3OXwNe9q1sO6aAbT2yR7bMQQ1+h6I02Upw4QNXuJk9x2wYaB26cCe6uHedH5N" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMUqgQDFW5C6RpMgmM/UWFSnJz29CBwT3ZGHkB" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 700px;
            width: 90%;
        }

        h1 {
            margin-bottom: 20px;
            color: #343a40;
        }

        p {
            margin-bottom: 30px;
            color: #6c757d;
        }

        .btn-custom {
            width: 200px;
            margin: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

            p {
                font-size: 14px;
            }
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
        }

        .footer-custom p {
            color: #343a40 !important;
        }

        .footer-custom a {
            color: #007bff !important;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-custom a:hover {
            color: #0056b3 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="display-4">Bem-vindo à Plataforma de Chamados de TI</h1>
        <p>Aqui você pode registrar problemas técnicos e sugestões de forma simples e rápida.
           Utilize os botões abaixo para acessar seu cadastro ou fazer login na plataforma.</p>
        <div>
            <a href="views/register.php" class="btn btn-primary btn-custom">
                <i class="fas fa-user-plus"></i> Cadastrar
            </a>
            <a href="views/login.php" class="btn btn-secondary btn-custom">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        </div>
    </div>

    <footer class="text-center footer-custom">
        <p>Desenvolvido por <a href="https://carlossdev.site/" target="_blank">Carlos Augusto</a></p>
    </footer>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
