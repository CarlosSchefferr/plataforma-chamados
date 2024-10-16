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
    <title>Editar Chamado</title>
    <style>
        body {
            background-color: #f0f4f8;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .form-label {
            font-weight: bold;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
        }
        .btn-custom:hover {
            background-color: #218838;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h2><i class="fas fa-edit"></i> Editar Chamado</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="update_ticket.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($chamado['id']) ?>">

                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?= htmlspecialchars(strip_tags($chamado['descricao'])) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="tipo_incidente" class="form-label">Tipo de Incidente</label>
                                <select class="form-select" id="tipo_incidente" name="tipo_incidente" required>
                                    <option value="hardware" <?= $chamado['tipo_incidente'] == 'hardware' ? 'selected' : '' ?>>Hardware</option>
                                    <option value="software" <?= $chamado['tipo_incidente'] == 'software' ? 'selected' : '' ?>>Software</option>
                                    <option value="rede" <?= $chamado['tipo_incidente'] == 'rede' ? 'selected' : '' ?>>Rede</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="aberto" <?= $chamado['status'] == 'aberto' ? 'selected' : '' ?>>Aberto</option>
                                    <option value="andamento" <?= $chamado['status'] == 'andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                    <option value="fechado" <?= $chamado['status'] == 'fechado' ? 'selected' : '' ?>>Fechado</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="anexos" class="form-label">Anexos</label>
                                <input type="file" class="form-control" id="anexos" name="anexos[]" multiple>
                                <small class="form-text text-muted">Selecione arquivos para anexar ao chamado. Você pode selecionar múltiplos arquivos.</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-custom px-4"><i class="fas fa-save"></i> Salvar Alterações</button>
                                <a href="detalhes_chamado.php?id=<?= $chamado['id'] ?>" class="btn btn-secondary px-4"><i class="fas fa-times"></i> Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-5">
        <p>&copy; <?= date('Y') ?> Sistema de Chamados de TI</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
