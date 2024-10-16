<?php
session_start();
require_once '../config/config.php';
require_once '../controllers/TicketController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticket_id'];
    $data = [
        'descricao' => $_POST['descricao'],
        'tipo_incidente' => $_POST['tipo_incidente'],
        'status' => $_POST['status'],
    ];

    $ticketController = new TicketController();
    $updated = $ticketController->updateTicket($ticketId, $data);

    if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'caminho/para/salvar/';
        $caminho = $uploadDir . basename($_FILES['anexo']['name']);
        
        if (move_uploaded_file($_FILES['anexo']['tmp_name'], $caminho)) {
            $stmt = $pdo->prepare("INSERT INTO anexos (chamado_id, caminho) VALUES (?, ?)");
            $stmt->execute([$ticketId, $caminho]);
        } else {
            header("Location: detalhes_chamado.php?id=$ticketId&msg=Erro ao fazer upload do anexo.");
            exit;
        }
    }

    if ($updated) {
        header("Location: detalhes_chamado.php?id=$ticketId&msg=Chamado atualizado com sucesso!");
        exit;
    } else {
        header("Location: detalhes_chamado.php?id=$ticketId&msg=Erro ao atualizar o chamado.");
        exit;
    }
}
