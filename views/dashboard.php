<?php
session_start();

require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT nome_completo FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $user_name = $user['nome_completo'];
} else {
    $user_name = "Usuário não encontrado";
}

$stmtOpen = $pdo->prepare("SELECT COUNT(*) AS total FROM chamados WHERE status = 'aberto' AND usuario_id = :id");
$stmtOpen->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtOpen->execute();
$open_tickets = $stmtOpen->fetch(PDO::FETCH_ASSOC)['total'];

$stmtInProgress = $pdo->prepare("SELECT COUNT(*) AS total FROM chamados WHERE status = 'andamento' AND usuario_id = :id");
$stmtInProgress->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtInProgress->execute();
$in_progress_tickets = $stmtInProgress->fetch(PDO::FETCH_ASSOC)['total'];

$stmtClosed = $pdo->prepare("SELECT COUNT(*) AS total FROM chamados WHERE status = 'fechado' AND usuario_id = :id");
$stmtClosed->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtClosed->execute();
$closed_tickets = $stmtClosed->fetch(PDO::FETCH_ASSOC)['total'];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard - Sistema de Chamados</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            margin-bottom: 20px;
            transition: transform 0.3s;
            border: none;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-header {
            font-size: 1.2rem;
        }

        .display-4 {
            font-size: 3rem;
        }

        .progress {
            height: 20px;
        }

        .icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        footer {
            margin-top: 50px;
            font-size: 0.9rem;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }

        .welcome-section h4 {
            font-size: 1.5rem;
            font-weight: 400;
            color: #555;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="btn btn-danger logout-btn">Sair</a>

        <div class="welcome-section">
            <h1>Bem-vindo, <?= htmlspecialchars($user_name) ?></h1>
            <h4>Aqui está o resumo dos seus chamados:</h4>
        </div>

        <div class="row text-center">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="icon">
                            <i class="bi bi-inbox-fill"></i>
                        </div>
                        <h5 class="card-header">Chamados Abertos</h5>
                        <h5 class="display-4"><?= htmlspecialchars($open_tickets) ?></h5>
                        <p class="card-text">Chamados aguardando resolução.</p>
                        <a href="abertura_chamado.php" class="btn btn-light">Abrir Novo Chamado</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5 class="card-header">Em Andamento</h5>
                        <h5 class="display-4"><?= htmlspecialchars($in_progress_tickets) ?></h5>
                        <p class="card-text">Chamados sendo resolvidos.</p>
                        <div class="progress">
                            <div class="progress-bar bg-light" role="progressbar" style="width: <?= ($in_progress_tickets / ($open_tickets + $in_progress_tickets + $closed_tickets)) * 100 ?>%;" aria-valuenow="<?= $in_progress_tickets ?>" aria-valuemin="0" aria-valuemax="100"><?= $in_progress_tickets ?> em andamento</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h5 class="card-header">Chamados Fechados</h5>
                        <h5 class="display-4"><?= htmlspecialchars($closed_tickets) ?></h5>
                        <p class="card-text">Chamados já resolvidos.</p>
                        <a href="listagem_chamados.php" class="btn btn-light">Ver Histórico</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; <?= date('Y') ?> Sistema de Chamados de TI</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
