<?php
require_once '../config/config.php';

class AuthController {
    public function register($data) {
        global $pdo;

        $nomeCompleto = $data['nome_completo'];
        $dataNascimento = $data['data_nascimento'];
        $email = $data['email'];
        $telefone = $data['telefone'];
        $whatsapp = $data['whatsapp'];
        $senha = password_hash($data['senha'], PASSWORD_BCRYPT);
        $cidade = $data['cidade'];
        $estado = $data['estado'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            return false;
        }

        $stmt = $pdo->prepare("INSERT INTO users (nome_completo, data_nascimento, email, telefone, whatsapp, senha, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nomeCompleto, $dataNascimento, $email, $telefone, $whatsapp, $senha, $cidade, $estado]);
    }

    public function login($email, $senha) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome_completo'];
            return true;
        }
        return false;
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../index.php");
        exit;
    }
}
?>
