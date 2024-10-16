<?php
require_once '../config/config.php';

class TicketController {
    public function createTicket($data) {
        global $pdo;

        $descricao = $data['descricao'];
        $tipoIncidente = $data['tipo_incidente'];
        $usuarioId = $data['usuario_id'];

        $stmt = $pdo->prepare("INSERT INTO chamados (descricao, tipo_incidente, usuario_id) VALUES (?, ?, ?)");

        if ($stmt->execute([$descricao, $tipoIncidente, $usuarioId])) {
            $chamadoId = $pdo->lastInsertId();

            if (isset($data['contatos']) && !empty($data['contatos'])) {
                $contatos = json_decode($data['contatos'], true);

                if (is_array($contatos)) {
                    foreach ($contatos as $contato) {
                        if (isset($contato['nome'], $contato['telefone'], $contato['observacao'])) {
                            $stmt = $pdo->prepare("INSERT INTO contatos (chamado_id, nome, telefone, observacao) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$chamadoId, $contato['nome'], $contato['telefone'], $contato['observacao']]);
                        }
                    }
                }
            }

            if (isset($_FILES['anexos']) && $_FILES['anexos']['error'] === UPLOAD_ERR_OK) {
                $caminho = 'caminho/para/salvar/' . basename($_FILES['anexo']['name']);
                
                if (strlen($caminho) > 65535) {
                    die("O caminho do arquivo excede o tamanho máximo permitido.");
                }
                
                $stmt = $pdo->prepare("INSERT INTO anexos (chamado_id, caminho) VALUES (?, ?)");
                $stmt->execute([$chamadoId, $caminho]);
            }

            return true;
        }

        return false;
    }

    public function getTicketsByUser($userId, $filterStatus = '', $filterType = '') {
        global $pdo;
    
        $query = "SELECT * FROM chamados WHERE usuario_id = ?";
        $params = [$userId];
    
        if (!empty($filterStatus)) {
            $query .= " AND status = ?";
            $params[] = $filterStatus;
        }
    
        if (!empty($filterType)) {
            $query .= " AND tipo_incidente = ?";
            $params[] = $filterType;
        }
    
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($tickets as &$ticket) {
            switch ($ticket['status']) {
                case 'aberto':
                    $ticket['status_class'] = 'status-open';
                    break;
                case 'andamento':
                    $ticket['status_class'] = 'status-in-progress';
                    break;
                case 'fechado':
                    $ticket['status_class'] = 'status-closed';
                    break;
                default:
                    $ticket['status_class'] = 'status-default';
            }
        }
    
        return $tickets;
    }
    
    public function getTicketDetails($ticketId, $usuarioId) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM chamados WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$ticketId, $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTicket($ticketId, $data) {
        global $pdo;

        $descricao = $data['descricao'];
        $tipoIncidente = $data['tipo_incidente'];
        $status = $data['status'];

        $stmt = $pdo->prepare("UPDATE chamados SET descricao = ?, tipo_incidente = ?, status = ? WHERE id = ?");
        return $stmt->execute([$descricao, $tipoIncidente, $status, $ticketId]);
    }

    public function deleteTicket($id) {
        global $pdo;

        $queryDeleteContatos = "DELETE FROM contatos WHERE chamado_id = :chamado_id";
        $stmtDeleteContatos = $pdo->prepare($queryDeleteContatos);
        $stmtDeleteContatos->bindParam(':chamado_id', $id, PDO::PARAM_INT);
        $stmtDeleteContatos->execute();

        $queryDeleteChamado = "DELETE FROM chamados WHERE id = :id";
        $stmtDeleteChamado = $pdo->prepare($queryDeleteChamado);

        if (!$stmtDeleteChamado) {
            return false;
        }

        $stmtDeleteChamado->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmtDeleteChamado->execute()) {
            return false;
        }

        return true;
    }
}
?>
