<?php
session_start();
require_once '../controllers/TicketController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$ticketController = new TicketController();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $tipo_incidente = $_POST['tipo_incidente'];
    $usuario_id = $_SESSION['user_id'];

    $anexos = [];
    if (!empty($_FILES['anexos']['name'][0])) {
        foreach ($_FILES['anexos']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['anexos']['error'][$key] !== UPLOAD_ERR_OK) {
                $error = "Erro ao fazer upload do arquivo: " . $_FILES['anexos']['name'][$key];
                break;
            }

            if ($_FILES['anexos']['size'][$key] > 2 * 1024 * 1024) {
                $error = "O arquivo " . $_FILES['anexos']['name'][$key] . " é muito grande. O limite é 2MB.";
                break;
            }

            $file_data = file_get_contents($tmp_name);
            $base64 = base64_encode($file_data);
            $anexos[] = $base64;
        }
    }


    $contatos = [];
    if (!empty($_POST['contatos'])) {
        foreach ($_POST['contatos'] as $contato) {
            if (!empty($contato['nome']) && !empty($contato['telefone'])) {
                $contatos[] = $contato;
            }
        }
    }


    if (empty($error)) {
        $data = [
            'descricao' => $descricao,
            'tipo_incidente' => $tipo_incidente,
            'usuario_id' => $usuario_id,
            'anexos' => json_encode($anexos),
            'contatos' => json_encode($contatos),
        ];

        if ($ticketController->createTicket($data)) {
            $success = "Chamado aberto com sucesso!";
        } else {
            $error = "Erro ao abrir chamado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css">
    <title>Abertura de Chamado</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .contato {
            margin-bottom: 10px;
        }
        .card {
            border: 1px solid #ced4da;
            border-radius: 0.5rem;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 1rem 0;
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Abertura de Chamado</h1>
        <p class="text-center">Por favor, preencha o formulário abaixo.</p>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="formChamado">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição do Problema:</label>
                        <textarea class="form-control" name="descricao" id="descricao" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_incidente" class="form-label">Tipo de Incidente:</label>
                        <select class="form-select" name="tipo_incidente" required>
                            <option value="">Selecione...</option>
                            <option value="Hardware">Hardware</option>
                            <option value="Software">Software</option>
                            <option value="Rede">Rede</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="anexos" class="form-label">Anexos:</label>
                        <input type="file" class="form-control" name="anexos[]" multiple accept="image/*,application/pdf">
                        <div class="form-text">Selecione um ou mais arquivos (imagens ou PDFs).</div>
                    </div>
                    <div id="contatosContainer" class="mb-3">
                        <label>Contatos Telefônicos:</label>
                        <div class="contato">
                            <input type="text" class="form-control mb-1" name="contatos[0][nome]" placeholder="Nome" required>
                            <input type="text" class="form-control mb-1 telefone" name="contatos[0][telefone]" placeholder="Telefone" required>
                            <textarea class="form-control mb-2" name="contatos[0][observacao]" placeholder="Observação"></textarea>
                            <button type="button" class="btn btn-danger remove-contato">Remover</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" id="addContato">Adicionar Contato</button>
                    <button type="submit" class="btn btn-primary mb-3">Registrar Chamado</button>
                    <a href="dashboard.php" class="btn btn-success mb-3">Voltar ao Dashboard</a>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#descricao').summernote({
                height: 200
            });


            let contatoIndex = 1;
            $('#addContato').click(function() {
                $('#contatosContainer').append(`
                    <div class="contato">
                        <input type="text" class="form-control mb-1" name="contatos[${contatoIndex}][nome]" placeholder="Nome" required>
                        <input type="text" class="form-control mb-1 telefone" name="contatos[${contatoIndex}][telefone]" placeholder="Telefone" required>
                        <textarea class="form-control mb-2" name="contatos[${contatoIndex}][observacao]" placeholder="Observação"></textarea>
                        <button type="button" class="btn btn-danger remove-contato">Remover</button>
                    </div>
                `);
                contatoIndex++;
            });


            $(document).on('click', '.remove-contato', function() {
                $(this).closest('.contato').remove();
            });
        });
    </script>
</body>
</html>
