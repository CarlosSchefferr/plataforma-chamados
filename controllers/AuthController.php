<?php
require_once '../config/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class AuthController {

    private function generateVerificationCode() {
        return rand(100000, 999999);
    }

    private function sendVerificationEmail($email, $code) {
        $phpmailer = new PHPMailer(true);

        try {
            $phpmailer->isSMTP();
            $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = 'f20bd97a2972fb';
            $phpmailer->Password = 'ad95c4db7f27cb';
            $phpmailer->Port = 2525;

            $phpmailer->setFrom('plataformachamados@gmail.com', 'Plataforma de Chamados');
            $phpmailer->addAddress($email);

            $phpmailer->isHTML(true);
            $phpmailer->CharSet = 'UTF-8';
            $phpmailer->Subject = 'Seu Código de Verificação';
            $phpmailer->Body = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 40px auto;
        }
        h1 {
            color: #333333;
            font-size: 24px;
        }
        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.6;
        }
        .code {
            font-size: 22px;
            font-weight: bold;
            color: #000;
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Verificação de E-mail</h1>
        <p>Olá,</p>
        <p>Obrigado por se cadastrar! Para concluir seu registro, por favor, use o código de verificação abaixo:</p>
        <div class="code">' . $code . '</div>
        <p>Insira este código na página de validação para verificar seu e-mail.</p>
        <p>Se você não solicitou este e-mail, por favor, ignore-o.</p>
        <div class="footer">
            <p>Atenciosamente,</p>
            <p>Equipe Plataforma de Chamados</p>
        </div>
    </div>
</body>
</html>';

            $phpmailer->send();
            return true;
        } catch (Exception $e) {
            echo "Erro ao enviar e-mail: {$phpmailer->ErrorInfo}";
            return false;
        }
    }

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

        $verificationCode = $this->generateVerificationCode();

        $stmt = $pdo->prepare("INSERT INTO users (nome_completo, data_nascimento, email, telefone, whatsapp, senha, cidade, estado, codigo_validacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$nomeCompleto, $dataNascimento, $email, $telefone, $whatsapp, $senha, $cidade, $estado, $verificationCode]);

        if ($result) {
            $this->sendVerificationEmail($email, $verificationCode);
            header("Location: ../views/email-verificacao.php?email=" . urlencode($email));
            exit;
        }

        return false;
    }

    public function login($email, $senha) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            if ($user['email_verificado'] == 0) {
                return "E-mail não verificado. Verifique seu e-mail para continuar.";
            }

            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome_completo'];
            return true;
        }

        return false;
    }

    public function verifyEmail($email, $code) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND codigo_validacao = ?");
        $stmt->execute([$email, $code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET email_verificado = 1 WHERE email = ?");
            $stmt->execute([$email]);
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
