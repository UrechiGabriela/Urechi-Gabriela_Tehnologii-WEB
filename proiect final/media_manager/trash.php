<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$mesaj = '';

$stmtUser = $pdo->prepare("SELECT email FROM users WHERE id = :id");
$stmtUser->execute(['id' => $user_id]);
$userData = $stmtUser->fetch();
$user_email = $userData ? $userData['email'] : 'User';

// stergere / restaurare / golire gunoi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'restore' && isset($_POST['image_id'])) {
        $stmtRestore = $pdo->prepare("UPDATE images SET is_deleted = 0 WHERE id = :id AND user_id = :user_id");
        $stmtRestore->execute(['id' => $_POST['image_id'], 'user_id' => $user_id]);
    } elseif ($action === 'delete_permanent' && isset($_POST['image_id'])) {
        $stmtGet = $pdo->prepare("SELECT filename FROM images WHERE id = :id AND user_id = :user_id AND is_deleted = 1");
        $stmtGet->execute(['id' => $_POST['image_id'], 'user_id' => $user_id]);
        $img = $stmtGet->fetch();
        if ($img) {
            if (file_exists('uploads/' . $img['filename'])) unlink('uploads/' . $img['filename']);
            $stmtDel = $pdo->prepare("DELETE FROM images WHERE id = :id AND user_id = :user_id AND is_deleted = 1");
            $stmtDel->execute(['id' => $_POST['image_id'], 'user_id' => $user_id]);
        }
    } elseif ($action === 'empty_trash') {
        $stmtAll = $pdo->prepare("SELECT filename FROM images WHERE user_id = :user_id AND is_deleted = 1");
        $stmtAll->execute(['user_id' => $user_id]);
        foreach ($stmtAll->fetchAll() as $img) {
            if (file_exists('uploads/' . $img['filename'])) unlink('uploads/' . $img['filename']);
        }
        $stmtDelAll = $pdo->prepare("DELETE FROM images WHERE user_id = :user_id AND is_deleted = 1");
        $stmtDelAll->execute(['user_id' => $user_id]);
    }
    header("Location: trash.php"); exit;
}

// cacul spatiu de gunoi
$stmtStorage = $pdo->prepare("SELECT 
    SUM(size_mb) as total_used,
    SUM(CASE WHEN is_deleted = 1 THEN size_mb ELSE 0 END) as trash_used
FROM images WHERE user_id = :user_id");
$stmtStorage->execute(['user_id' => $user_id]);
$storageResult = $stmtStorage->fetch();

$total_used = $storageResult['total_used'] ?? 0;
$trash_used = $storageResult['trash_used'] ?? 0;

$stmtQuota = $pdo->prepare("SELECT max_storage_mb FROM users WHERE id = :user_id");
$stmtQuota->execute(['user_id' => $user_id]);
$max_storage = $stmtQuota->fetch()['max_storage_mb'];

// Procentaje
$pct_total = ($total_used / $max_storage) * 100;
$pct_trash = ($trash_used / $max_storage) * 100;
if ($pct_total > 100) $pct_total = 100;

$stmtImages = $pdo->prepare("SELECT * FROM images WHERE user_id = :user_id AND is_deleted = 1 ORDER BY upload_date DESC");
$stmtImages->execute(['user_id' => $user_id]);
$images = $stmtImages->fetchAll();

// cookie theme
$user_prefix = explode('@', $user_email)[0]; 
$cookie_name = 'theme_' . $user_prefix;
$theme = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : 'light';

require 'views/trash_view.php';

?>