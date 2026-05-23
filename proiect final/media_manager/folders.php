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

// calcul spatiu de stocare
$stmtStorage = $pdo->prepare("SELECT 
    SUM(CASE WHEN is_deleted = 0 THEN size_mb ELSE 0 END) as total_used,
    SUM(CASE WHEN is_deleted = 1 THEN size_mb ELSE 0 END) as trash_used
FROM images WHERE user_id = :user_id");
$stmtStorage->execute(['user_id' => $user_id]);
$storageResult = $stmtStorage->fetch();
$total_used = $storageResult['total_used'] ?? 0;
$trash_used = $storageResult['trash_used'] ?? 0;

$stmtQuota = $pdo->prepare("SELECT max_storage_mb FROM users WHERE id = :user_id");
$stmtQuota->execute(['user_id' => $user_id]);
$quotaResult = $stmtQuota->fetch();
$max_storage = $quotaResult['max_storage_mb'];

$storage_percentage = ($total_used / $max_storage) * 100;
$trash_percentage = ($trash_used / $max_storage) * 100;
if ($storage_percentage + $trash_percentage > 100) {
    if ($storage_percentage > 100) $storage_percentage = 100;
    $trash_percentage = 100 - $storage_percentage;
}
$procent_afisat = number_format($storage_percentage, 1);
$trash_afisat = number_format($trash_percentage, 1);

// creare / stergere foldere
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create_folder') {
        $folder_name = trim($_POST['folder_name']);
        if (!empty($folder_name)) {
            $stmtCreate = $pdo->prepare("INSERT INTO folders (user_id, name) VALUES (:user_id, :name)");
            try {
                $stmtCreate->execute(['user_id' => $user_id, 'name' => $folder_name]);
                header("Location: folders.php");
                exit;
            } catch (\PDOException $e) {
                $mesaj = "Error creating folder.";
            }
        } else {
            $mesaj = "Folder name cannot be empty.";
        }
    } elseif ($_POST['action'] === 'delete_folder') {
        $folder_id = $_POST['folder_id'];
        $stmtDelete = $pdo->prepare("DELETE FROM folders WHERE id = :id AND user_id = :user_id");
        try {
            $stmtDelete->execute(['id' => $folder_id, 'user_id' => $user_id]);
            header("Location: folders.php");
            exit;
        } catch (\PDOException $e) {
            $mesaj = "Error deleting folder.";
        }
    }
}

// preluam toate folderele utilizatorului
$stmtFolders = $pdo->prepare("SELECT * FROM folders WHERE user_id = :user_id ORDER BY created_at DESC");
$stmtFolders->execute(['user_id' => $user_id]);
$folders = $stmtFolders->fetchAll();

// cookie theme
$user_prefix = explode('@', $user_email)[0]; 
$cookie_name = 'theme_' . $user_prefix;
$theme = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : 'light';


require 'views/folders_view.php';

?>


    