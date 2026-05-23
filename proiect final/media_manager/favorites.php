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

// procentaj pentru graficul circular
$storage_percentage = ($total_used / $max_storage) * 100;
$trash_percentage = ($trash_used / $max_storage) * 100;
if ($storage_percentage + $trash_percentage > 100) {
    if ($storage_percentage > 100) $storage_percentage = 100;
    $trash_percentage = 100 - $storage_percentage;
}
$procent_afisat = number_format($storage_percentage, 1);
$trash_afisat = number_format($trash_percentage, 1);

// upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagine'])) {
    $file = $_FILES['imagine'];
    $titlu = isset($_POST['titlu']) ? trim($_POST['titlu']) : '';
    if (empty($titlu)) $titlu = "Untitled Image";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($file['tmp_name']);
        
        if (in_array($file_type, $allowed_types)) {
            $simulated_size = rand(500, 900);
            if (($total_used + $trash_used + $simulated_size) <= $max_storage) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid('img_', true) . '.' . $ext;
                
                if (!is_dir('uploads')) mkdir('uploads', 0777, true);
                $destination = 'uploads/' . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $stmtInsert = $pdo->prepare("INSERT INTO images (user_id, filename, title, size_mb, is_favorite) VALUES (:user_id, :filename, :title, :size, 1)");
                    $stmtInsert->execute([
                        'user_id' => $user_id,
                        'filename' => $new_filename,
                        'title' => $titlu,
                        'size' => $simulated_size
                    ]);
                    header("Location: favorites.php");
                    exit;
                } else { $mesaj = "Error saving file."; }
            } else { $mesaj = "Storage limit exceeded!"; }
        } else { $mesaj = "Only images (JPG, PNG, GIF, WEBP) are allowed."; }
    } else { $mesaj = "Select a valid file."; }
}

// actiunile pe imagini
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $image_id = $_POST['image_id'];
    
    if ($action === 'delete') {
        $stmtDelete = $pdo->prepare("UPDATE images SET is_deleted = 1 WHERE id = :id AND user_id = :user_id");
        $stmtDelete->execute(['id' => $image_id, 'user_id' => $user_id]);
    } elseif ($action === 'toggle_favorite') {
        $stmtFav = $pdo->prepare("UPDATE images SET is_favorite = 1 - is_favorite WHERE id = :id AND user_id = :user_id");
        $stmtFav->execute(['id' => $image_id, 'user_id' => $user_id]);
    } elseif ($action === 'edit_title') {
        $new_title = trim($_POST['new_title']);
        if (!empty($new_title)) {
            $stmtEdit = $pdo->prepare("UPDATE images SET title = :title WHERE id = :id AND user_id = :user_id");
            $stmtEdit->execute(['title' => $new_title, 'id' => $image_id, 'user_id' => $user_id]);
        }
    } elseif ($action === 'move_to_folder') {
        $new_folder_id = !empty($_POST['new_folder_id']) ? $_POST['new_folder_id'] : null;
        $stmtMove = $pdo->prepare("UPDATE images SET folder_id = :folder_id WHERE id = :id AND user_id = :user_id");
        $stmtMove->execute(['folder_id' => $new_folder_id, 'id' => $image_id, 'user_id' => $user_id]);
    }
    
    header("Location: favorites.php"); exit;
}

// preluam folderele
$stmtFolders = $pdo->prepare("SELECT id, name FROM folders WHERE user_id = :user_id ORDER BY name ASC");
$stmtFolders->execute(['user_id' => $user_id]);
$foldere_utilizator = $stmtFolders->fetchAll();

// preluam imaginile favorite
$stmtImages = $pdo->prepare("SELECT * FROM images WHERE user_id = :user_id AND is_deleted = 0 AND is_favorite = 1 ORDER BY upload_date DESC");
$stmtImages->execute(['user_id' => $user_id]);
$images = $stmtImages->fetchAll();

// cookie theme
$user_prefix = explode('@', $user_email)[0]; 
$cookie_name = 'theme_' . $user_prefix;
$theme = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : 'light';


require 'views/favorites_view.php';


?>


   