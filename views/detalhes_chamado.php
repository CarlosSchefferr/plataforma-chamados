<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/config.php';

if (!isset($_GET['id'])) {
    header("Location: listagem_chamados.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM chamados WHERE id = ? AND usuario_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$chamado = $stmt->fetch();

if (!$chamado) {
    header("Location: listagem_chamados.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Detalhes do Chamado</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .badge-status {
            font-size: 1rem;
            padding: 0.5rem;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Detalhes do Chamado</h1>

        <div class="card">
            <div class="card-header">
                Chamado: <strong><?= htmlspecialchars($chamado['id']) ?></strong>
            </div>
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle"></i> Descrição</h5>
                <p class="card-text"><?= nl2br(htmlspecialchars(strip_tags($chamado['descricao']))) ?></p>

                <h5 class="card-title"><i class="fas fa-tools"></i> Tipo de Incidente</h5>
                <p class="card-text"><?= htmlspecialchars($chamado['tipo_incidente']) ?></p>

                <h5 class="card-title"><i class="fas fa-calendar-alt"></i> Data de Abertura</h5>
                <p class="card-text"><?= date('d/m/Y H:i', strtotime($chamado['criado_em'])) ?></p>

                <h5 class="card-title"><i class="fas fa-clipboard-check"></i> Status</h5>
                <p class="card-text"><span class="badge bg-info badge-status"><?= htmlspecialchars($chamado['status']) ?></span></p>

                <?php if (!empty($chamado['anexos'])): ?>
                    <h5 class="card-title"><i class="fas fa-paperclip"></i> Anexos</h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($chamado['anexos'])) ?></p>
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <a href="editar_chamado.php?id=<?= $chamado['id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Editar Chamado</a>
                <a href="listagem_chamados.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para a Lista</a>
            </div>
        </div>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; <?= date('Y') ?> Sistema de Chamados de TI</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
