<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_email = '';
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$usr = $stmt->fetch();
if($usr) {
    $user_email = $usr['email'];
}

// actiuni stergere / adaugare spatiu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $target_user_id = $_POST['target_user_id'] ?? 0;

        if ($action === 'delete_user' && $target_user_id) {
            $stmtImgs = $pdo->prepare("SELECT filename FROM images WHERE user_id = :uid");
            $stmtImgs->execute(['uid' => $target_user_id]);
            foreach($stmtImgs->fetchAll() as $img) {
                if (file_exists('uploads/' . $img['filename'])) {
                    unlink('uploads/' . $img['filename']);
                }
            }
            $pdo->prepare("DELETE FROM images WHERE user_id = :uid")->execute(['uid' => $target_user_id]);
            $pdo->prepare("DELETE FROM folders WHERE user_id = :uid")->execute(['uid' => $target_user_id]);
            $pdo->prepare("DELETE FROM users WHERE id = :uid")->execute(['uid' => $target_user_id]);

        } elseif ($action === 'add_gb' && $target_user_id && isset($_POST['mb_amount'])) {
            $extra_mb = (int)$_POST['mb_amount'];
            $pdo->prepare("UPDATE users SET max_storage_mb = max_storage_mb + :extra WHERE id = :uid")
                ->execute(['extra' => $extra_mb, 'uid' => $target_user_id]);
        }
        
        header("Location: admin.php");
        exit;
    }
}
// datele pentru statistici generale
$stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'")->fetchColumn(),
    'images' => $pdo->query("SELECT COUNT(*) FROM images")->fetchColumn(),
    'space' => $pdo->query("SELECT SUM(size_mb) FROM images")->fetchColumn() ?? 0,
    'total_quota' => $pdo->query("SELECT SUM(max_storage_mb) FROM users WHERE role != 'admin'")->fetchColumn() ?? 0
];

// datele pentru lista de utilizatori
$stmtUsers = $pdo->query("
    SELECT id, email, created_at, max_storage_mb, role,
    (SELECT COUNT(id) FROM images WHERE user_id=users.id) as img_count,
    (SELECT SUM(size_mb) FROM images WHERE user_id=users.id) as used_mb
    FROM users 
    WHERE role != 'admin'
    ORDER BY id DESC
");
$users = $stmtUsers->fetchAll();

require 'views/admin_view.php';
?>
