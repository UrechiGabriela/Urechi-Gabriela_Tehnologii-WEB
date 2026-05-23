<?php
session_start();

require 'includes/db.php';

$mesaj = '';
$mesaj_tip = 'error';

// validarea contului prin email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $parola = $_POST['password'];
    $confirma_parola = $_POST['confirm_password'];

    if (empty($email) || empty($parola) || empty($confirma_parola)) {
        $mesaj = "Please fill in all fields!";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mesaj = "Please enter a valid email address!";
    }
    elseif (!preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[\W_]).{8,}$/', $parola)) {
        $mesaj = "The password must be at least 8 characters and contain letters, numbers, and at least one special character!";
    }
    elseif ($parola !== $confirma_parola) {
        $mesaj = "Passwords do not match!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        if ($stmt->rowCount() > 0) {
            $mesaj = "This email is already registered!";
        } else {
            $parola_hash = password_hash($parola, PASSWORD_DEFAULT);
            $token_validare = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare("INSERT INTO users (email, password, verify_token, is_verified) VALUES (:email, :password, :token, 0)");
            
            try {
                $stmt->execute([
                    'email' => $email,
                    'password' => $parola_hash,
                    'token' => $token_validare
                ]);

                $link_validare = "http://localhost/media_manager/verify.php?token=" . $token_validare;
                
                $subiect = "Account Verification - Momento";
                $mesaj_email = "Welcome to Momento!\n\nTo verify your account and activate your storage, please click the link below:\n\n" . $link_validare . "\n\nThank you,\nMomento Team";
                
                $headers = "From: no-reply@momento.localhost\r\n" .
                           "Reply-To: no-reply@momento.localhost\r\n" .
                           "Content-Type: text/plain; charset=UTF-8\r\n" .
                           "X-Mailer: PHP/" . phpversion();

                $mail_sent = @mail($email, $subiect, $mesaj_email, $headers);
                
                $mesaj_tip = 'success';
                $mesaj = "Account created successfully! Please check your email to verify your account.";
                
                if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
                    $mesaj .= "<br><br><small style='color: #059669;'><b>Localhost Test:</b> <a href='$link_validare' style='color: #059669; text-decoration: underline;'>Click here to verify</a></small>";
                }
                
            } catch (\PDOException $e) {
                $mesaj = "An error occurred while creating the account.";
            }
        }
    }
}

require 'views/register_view.php';

?>

