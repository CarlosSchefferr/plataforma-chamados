<?php
session_start();
require_once '../controllers/TicketController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$ticketController = new TicketController();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_ids'])) {
    $idsToRemove = explode(',', $_POST['remove_ids']);
    foreach ($idsToRemove as $id) {
        $ticketController->deleteTicket($id);
    }
}


$filterStatus = isset($_POST['filter_status']) ? $_POST['filter_status'] : '';
$filterType = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';


$chamados = $ticketController->getTicketsByUser($_SESSION['user_id'], $filterStatus, $filterType);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+Yv9f53f+M1w8e8f3eZ2hA+PmR+eD5RmD3JZ2r4B" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Histórico de Chamados</title>
    <style>
        body {
            background-color: #e9ecef;
        }
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .status-label {
            font-weight: bold;
        }
        .status-aberto {
            color: #198754;
        }
        .status-andamento {
            color: #ffc107;
        }
        .status-fechado {
            color: #dc3545;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        .filter-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Histórico de Chamados</h1>

        <div class="text-end mb-3">
            <a href="dashboard.php" class="btn btn-success"><i class="fas fa-arrow-left"></i> Voltar para o Dashboard</a>
        </div>

        <!-- Formulário de filtro -->
        <div class="filter-container">
            <form method="POST" action="" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <select name="filter_status" class="form-select" aria-label="Filtrar por Status">
                            <option value="">Todos os Status</option>
                            <option value="Aberto" <?= $filterStatus === 'Aberto' ? 'selected' : '' ?>>Aberto</option>
                            <option value="Andamento" <?= $filterStatus === 'Andamento' ? 'selected' : '' ?>>Andamento</option>
                            <option value="Fechado" <?= $filterStatus === 'Fechado' ? 'selected' : '' ?>>Fechado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="filter_type" class="form-select" aria-label="Filtrar por Tipo">
                            <option value="">Todos os Tipos</option>
                            <option value="Software" <?= $filterType === 'Software' ? 'selected' : '' ?>>Software</option>
                            <option value="Rede" <?= $filterType === 'Rede' ? 'selected' : '' ?>>Rede</option>
                            <option value="Hardware" <?= $filterType === 'Hardware' ? 'selected' : '' ?>>Hardware</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="text-end mb-3">
            <button id="toggleSelect" class="btn btn-warning"><i class="fas fa-check-square"></i> Selecionar Todos</button>
            <button id="removeSelected" class="btn btn-danger" style="display:none;"><i class="fas fa-trash-alt"></i> Remover</button>
        </div>

        <form id="removeForm" method="POST" action="">
            <div class="row">
                <?php if (!empty($chamados)): ?>
                    <?php foreach ($chamados as $chamado): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card bg-light">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="checkbox-container">
                                        <input type="checkbox" class="select-checkbox" data-id="<?= htmlspecialchars($chamado['id']) ?>">
                                        <h5 class="mb-0 ms-2">Chamado: <?= htmlspecialchars($chamado['id']) ?></h5>
                                    </div>
                                    <span class="status-label status-<?= strtolower($chamado['status']) ?>">
                                        <?= htmlspecialchars($chamado['status']) ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= htmlspecialchars(strip_tags($chamado['descricao'] ?? 'Descrição não disponível')) ?></p>
                                    <p class="card-text">
                                        <small>Tipo: <strong><?= htmlspecialchars($chamado['tipo_incidente'] ?? 'Indefinido') ?></strong></small>
                                    </p>
                                    <p class="card-text">
                                        <small>Data de Abertura: <strong><?= isset($chamado['criado_em']) ? date('d/m/Y', strtotime($chamado['criado_em'])) : 'Data não disponível' ?></strong></small>
                                    </p>
                                    <a href="detalhes_chamado.php?id=<?= htmlspecialchars($chamado['id']) ?>" class="btn btn-primary"><i class="fas fa-info-circle"></i> Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center" role="alert">
                            Nenhum chamado encontrado.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <input type="hidden" name="remove_ids" id="remove_ids">
        </form>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectAll = false;

            $('#toggleSelect').on('click', function() {
                selectAll = !selectAll;
                $('.select-checkbox').prop('checked', selectAll);
                $(this).html(selectAll ? '<i class="fas fa-minus-square"></i> Deselecionar Todos' : '<i class="fas fa-check-square"></i> Selecionar Todos');
                toggleRemoveButton();
            });

            $('.select-checkbox').on('change', function() {
                toggleRemoveButton();
            });

            function toggleRemoveButton() {
                const anyChecked = $('.select-checkbox:checked').length > 0;
                $('#removeSelected').toggle(anyChecked);
            }

            $('#removeSelected').on('click', function() {
                const selectedIds = $('.select-checkbox:checked').map(function() {
                    return $(this).data('id');
                }).get();

                if (selectedIds.length > 0) {
                    if (confirm('Você tem certeza que deseja remover os chamados selecionados?')) {
                        $('#remove_ids').val(selectedIds.join(','));
                        $('#removeForm').submit();
                    }
                }
            });
        });
    </script>
</body>
</html>
