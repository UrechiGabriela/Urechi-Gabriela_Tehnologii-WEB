<?php
require 'includes/db.php';

$mesaj = '';

if (isset($_GET['token']) && !empty(trim($_GET['token']))) {
    $token = trim($_GET['token']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE verify_token = :token AND is_verified = 0");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $updateStmt = $pdo->prepare("UPDATE users SET is_verified = 1, verify_token = NULL WHERE id = :id");
        
        try {
            $updateStmt->execute(['id' => $user['id']]);
            $mesaj = "Your account has been successfully verified! You can now log in.";
        } catch (\PDOException $e) {
            $mesaj = "An error occurred while updating the database.";
        }
    } else {
        $mesaj = "This link is invalid or the account has already been verified.";
    }
} else {
    $mesaj = "No verification code was provided.";
}

require 'views/verify_view.php';

?>
