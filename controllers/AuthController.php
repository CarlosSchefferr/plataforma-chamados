<?php
require_once '../config/config.php';

class AuthController {
    public function register($data) {
        global $pdo;

        $name = $data['name'];
        $birthdate = $data['birthdate'];
        $email = $data['email'];
        $phone = $data['phone'];
        $whatsapp = $data['whatsapp'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT); // Hash da senha
        $city = $data['city'];
        $state = $data['state'];

        $stmt = $pdo->prepare("INSERT INTO users (name, birthdate, email, phone, whatsapp, password, city, state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $birthdate, $email, $phone, $whatsapp, $password, $city, $state]);
    }

    public function login($email, $password) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
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
