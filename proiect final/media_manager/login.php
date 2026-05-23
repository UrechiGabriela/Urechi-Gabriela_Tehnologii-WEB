<?php
session_start();
require 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    if($_SESSION['role'] === 'admin') { header("Location: admin.php"); } else { header("Location: dashboard.php"); }
    exit;
}

$mesaj = '';

// verificam data utilizatorul are cookie cu remember me 
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $stmt = $pdo->prepare("SELECT id, role FROM users WHERE remember_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if($_SESSION['role'] === 'admin') { header("Location: admin.php"); } else { header("Location: dashboard.php"); }
        exit;
    }
}

// formularul de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $parola = $_POST['password'];
    $tine_ma_minte = isset($_POST['remember_me']); 

    if (empty($email) || empty($parola)) {
        $mesaj = "Please enter your email and password!";
    } else {
        $stmt = $pdo->prepare("SELECT id, password, role, is_verified FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            if ($user['is_verified'] == 0) {
                $mesaj = "Your account is not verified. Please check your email.";
            } else {
                if (password_verify($parola, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];

                    if ($tine_ma_minte) {
                        $token = bin2hex(random_bytes(32)); 
                        
                        $updateToken = $pdo->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
                        $updateToken->execute(['token' => $token, 'id' => $user['id']]);

                        setcookie('remember_me', $token, time() + (86400 * 30), "/");
                    }

                    if($_SESSION['role'] === 'admin') { header("Location: admin.php"); } else { header("Location: dashboard.php"); }
                    exit;
                } else {
                    $mesaj = "Incorrect password / email!";
                }
            }
        } else {
            $mesaj = "No account associated with this email!";
        }
    }
}

require 'views/login_view.php';

?>


   