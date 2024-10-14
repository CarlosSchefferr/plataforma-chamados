<!-- listagem_chamados.php -->
<?php
session_start();
require 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM chamados WHERE usuario_id = :usuario_id ORDER BY data_criacao DESC");
$stmt->execute(['usuario_id' => $_SESSION['usuario_id']]);
$chamados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Chamados</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Meus Chamados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chamados as $chamado): ?>
                    <tr>
                        <td><?= $chamado['id'] ?></td>
                        <td><?= $chamado['descricao'] ?></td>
                        <td><?= $chamado['tipo_incidente'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($chamado['data_criacao'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="abertura_chamado.php" class="btn btn-primary">Abrir Novo Chamado</a>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
