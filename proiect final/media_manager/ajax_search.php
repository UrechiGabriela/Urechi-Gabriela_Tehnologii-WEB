<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = $_SESSION['user_id'];
$search_term = isset($_GET['q']) ? trim($_GET['q']) : '';
$sql = "SELECT * FROM images WHERE user_id = :user_id AND is_deleted = 0 AND title LIKE :search";
$params = [
    'user_id' => $user_id,
    'search' => '%' . $search_term . '%'
];
$sql .= " ORDER BY upload_date DESC";

$stmtImages = $pdo->prepare($sql);
$stmtImages->execute($params);
$images = $stmtImages->fetchAll();

if (empty($images)) {
    echo '<p class="empty-search-msg">No images found matching your search.</p>';
} else {
    foreach ($images as $img) {
        $titlu_sigur = htmlspecialchars($img['title']);
        $titlu_js = addslashes($img['title']);
        $filename = htmlspecialchars($img['filename']);
        $data_formatata = date('l, j F Y', strtotime($img['upload_date']));
        $fav_style = ($img['is_favorite'] == 1) ? 'color:#ffc107;' : '';

        echo '
        <div class="image-card">
            <img src="uploads/' . $filename . '" alt="Image" class="zoom-cursor" onclick="openLightbox(\'uploads/' . $filename . '\')">
            <div class="image-info">
                <div class="image-title-container">
                    <span class="image-title" title="' . $titlu_sigur . '">' . $titlu_sigur . '</span>
                    <button class="btn-edit" title="Editează Titlul" onclick="openRenameModal(' . $img['id'] . ', \'' . $titlu_js . '\')">✎</button>
                </div>
                <div class="image-meta">
                    <span class="image-size">' . $img['size_mb'] . ' MB</span>
                    <div class="action-group">
                        <button class="action-btn" title="Move to Folder" onclick="openMoveModal(' . $img['id'] . ')">⚲</button>
                        <form method="POST" action="dashboard.php" class="margin-0">
                            <input type="hidden" name="action" value="toggle_favorite">
                            <input type="hidden" name="image_id" value="' . $img['id'] . '">
                            <button type="submit" class="action-btn ' . ($img['is_favorite'] == 1 ? 'fav-active' : '') . '">★</button>
                        </form>
                        <button type="button" class="action-btn btn-del" onclick="openDeleteFileModal(' . $img['id'] . ')">✗</button>
                    </div>
                </div>
                <span class="image-date">' . $data_formatata . '</span>
            </div>
        </div>';
    }
}
?>