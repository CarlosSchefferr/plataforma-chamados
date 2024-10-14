<?php
session_start();
require 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $tipo_incidente = $_POST['tipo_incidente'];
    $contatos = $_POST['contatos'];
    $anexos = $_FILES['anexos'];

    if (empty($descricao) || empty($tipo_incidente)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO chamados (usuario_id, descricao, tipo_incidente, data_criacao) VALUES (:usuario_id, :descricao, :tipo_incidente, NOW())");
        $stmt->execute([
            'usuario_id' => $_SESSION['usuario_id'],
            'descricao' => $descricao,
            'tipo_incidente' => $tipo_incidente
        ]);

        $chamado_id = $pdo->lastInsertId();

        foreach ($contatos as $contato) {
            $stmt = $pdo->prepare("INSERT INTO contatos (chamado_id, nome, telefone, observacao) VALUES (:chamado_id, :nome, :telefone, :observacao)");
            $stmt->execute([
                'chamado_id' => $chamado_id,
                'nome' => $contato['nome'],
                'telefone' => $contato['telefone'],
                'observacao' => $contato['observacao']
            ]);
        }

        foreach ($anexos['tmp_name'] as $key => $tmp_name) {
            if ($anexos['error'][$key] == 0) {
                $anexo_data = base64_encode(file_get_contents($tmp_name));
                $stmt = $pdo->prepare("INSERT INTO anexos (chamado_id, arquivo) VALUES (:chamado_id, :arquivo)");
                $stmt->execute(['chamado_id' => $chamado_id, 'arquivo' => $anexo_data]);
            }
        }

        header('Location: listagem_chamados.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura de Chamado</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Abertura de Chamado</h2>
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= $erro ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição do Problema</label>
                <textarea class="form-control" id="descricao" name="descricao" required></textarea>
            </div>
            <div class="mb-3">
                <label for="tipo_incidente" class="form-label">Tipo de Incidente</label>
                <select class="form-control" id="tipo_incidente" name="tipo_incidente" required>
                    <option value="">Selecione</option>
                    <option value="hardware">Hardware</option>
                    <option value="software">Software</option>
                    <option value="rede">Rede</option>
                    <option value="outro">Outro</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="contatos" class="form-label">Contatos (Nome e Telefone)</label>
                <div id="contatos-container">
                    <div class="mb-2 contato">
                        <input type="text" class="form-control" name="contatos[0][nome]" placeholder="Nome" required>
                        <input type="text" class="form-control mt-1" name="contatos[0][telefone]" placeholder="Telefone" required>
                        <textarea class="form-control mt-1" name="contatos[0][observacao]" placeholder="Observação"></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mt-2" id="add-contato">Adicionar Contato</button>
            </div>
            <div class="mb-3">
                <label for="anexos" class="form-label">Anexos</label>
                <input type="file" class="form-control" id="anexos" name="anexos[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Chamado</button>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#descricao').summernote();

            $('#add-contato').click(function() {
                const index = $('#contatos-container .contato').length;
                $('#contatos-container').append(`
                    <div class="mb-2 contato">
                        <input type="text" class="form-control" name="contatos[${index}][nome]" placeholder="Nome" required>
                        <input type="text" class="form-control mt-1" name="contatos[${index}][telefone]" placeholder="Telefone" required>
                        <textarea class="form-control mt-1" name="contatos[${index}][observacao]" placeholder="Observação"></textarea>
                    </div>
                `);
            });
        });
    </script>
</body>
</html>
