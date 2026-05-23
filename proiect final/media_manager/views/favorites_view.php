<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favourites</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/favorites_style.css">
    </head>
<body class="view-favorites <?php echo ($theme === 'dark') ? 'dark-mode' : ''; ?>">

<input type="checkbox" id="mobile-menu-toggle" style="display: none;">
<input type="checkbox" id="settings-menu-toggle" style="display: none;">

<aside class="sidebar">
    <div class="brand">Momento</div>
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-item"><span>🖥</span> My Files</a>
        <a href="folders.php" class="nav-item"><span>📁</span> Folders</a>
        <a href="favorites.php" class="nav-item active"><span>☆</span> Favorite</a>
        <a href="trash.php" class="nav-item"><span>🗑</span> Trash</a>
       
    </nav>
</aside>

<label for="mobile-menu-toggle" class="mobile-menu-overlay"></label>
<label for="settings-menu-toggle" class="settings-menu-overlay"></label>
<label for="mobile-menu-toggle" class="hamburger-button">
    <span></span>
    <span></span>
    <span></span>
</label>
<label for="settings-menu-toggle" class="settings-button">
    <span>⚙️</span>
</label>



<div class="main-content">
    <div class="content-area">
        <main class="middle-panel">
            <div class="page-header">
                <h2 class="page-title-text">Favorites</h2>
            </div>

            <div class="gallery">
                <?php if (empty($images)): ?>
                    <p class="empty-state-text">You haven't starred any files yet. Go to Dashboard and click the ★ icon on an image.</p>
                <?php else: ?>
                    <?php foreach ($images as $img): ?>
                        <div class="image-card">
                            <img src="uploads/<?php echo htmlspecialchars($img['filename']); ?>" alt="Image" onclick="openLightbox('uploads/<?php echo htmlspecialchars($img['filename']); ?>')">
                            <div class="image-info">
                                <div class="image-title-container">
                                    <span class="image-title"><?php echo htmlspecialchars($img['title']); ?></span>
                                    <button class="btn-edit" onclick="openRenameModal(<?php echo $img['id']; ?>, '<?php echo addslashes($img['title']); ?>')">✎</button>
                                </div>
                                <div class="image-meta">
                                    <span class="image-size"><?php echo $img['size_mb']; ?> MB</span>
                                    <div class="action-group">
                                        <button class="action-btn" title="Move to Folder" onclick="openMoveModal(<?php echo $img['id']; ?>)">⚲</button>
                                        <form method="POST" action="favorites.php" class="inline-form">
                                            <input type="hidden" name="action" value="toggle_favorite">
                                            <input type="hidden" name="image_id" value="<?php echo $img['id']; ?>">
                                            <button type="submit" class="action-btn fav-active" title="Remove from Favourites">★</button>
                                        </form>
                                        <button type="button" class="action-btn btn-del" onclick="openDeleteFromFavoritesModal(<?php echo $img['id']; ?>)" title="Delete">x</button>
                                    </div>
                                </div>
                                <span class="image-date"><?php echo date('l, d M Y', strtotime($img['upload_date'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
<aside class="right-panel">
    <div class="user-dropdown" tabindex="0">
        <div class="user-profile-right">
            <span class="user-avatar"><?php echo strtoupper(substr($user_email, 0, 1)); ?></span>
            <span><?php echo htmlspecialchars(explode('@', $user_email)[0]); ?> ⏷</span>
        </div>
        <div class="dropdown-content">
            <a href="logout.php">Log Out</a>
        </div>
    </div>

    <div class="upload-card clean-style-6">
        <h3 class="widget-title text-accent">Upload Files</h3>
        <form action="favorites.php" method="POST" enctype="multipart/form-data" class="clean-style-8">
            <input type="text" name="titlu" placeholder="File Name" required class="clean-style-9">
            <input type="hidden" name="folder_id" value="<?php echo isset($current_folder_id) ? $current_folder_id : ''; ?>">
            <label class="file-input-label">
                📁 Choose File
                <input type="file" name="imagine" required accept="image/png, image/jpeg, image/webp" style="display: none;">
            </label>
            <button type="submit" class="btn-upload clean-style-10">Upload Now</button>
        </form>
    </div>
    <div>
        <div class="storage-chart-container">
            <div class="donut-chart" style="background: conic-gradient(#4fd1c5 <?php echo $storage_percentage; ?>%, #fbd38d <?php echo $storage_percentage; ?>% <?php echo $storage_percentage + $trash_percentage; ?>%, #edf2f7 0);">
                <div class="inner-circle">
                    <span class="storage-value"><?php echo number_format($total_used + $trash_used, 1); ?> MB</span>
                    <span class="storage-label"><?php echo number_format($max_storage, 0); ?> MB Total</span>
                </div>
            </div>
        </div>
        <div class="storage-legend">
            <div><span class="legend-bullet--active">•</span> <?php echo $procent_afisat; ?>% Active Files</div>
            <div><span class="legend-bullet--trash">•</span> <?php echo $trash_afisat; ?>% Recycle Bin</div>
        </div>
    </div>
</aside>
    </div>
</div>

<div id="deleteFavoriteModal" class="modal-overlay">
    <div class="modal-content text-center">
        <div class="icon-danger">!</div>
        <h3 style="margin-top: 0;">Delete File</h3>
        <p style="color: var(--text-muted); margin-bottom: 25px;">Are you sure you want to move this file to trash?</p>
        <form method="POST" action="favorites.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="image_id" id="favorite_delete_id" value="">
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteFromFavoritesModal()" class="modal-btn btn-cancel-modal">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<div id="renameModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Rename File</h3>
        <form method="POST" action="favorites.php">
            <input type="hidden" name="action" value="edit_title">
            <input type="hidden" name="image_id" id="rename_image_id" value="">
            
            <input type="text" name="new_title" id="rename_new_title" class="rename-input" required>
            
            <div class="modal-actions">
                <button type="button" onclick="closeRenameModal()" class="btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn-submit-modal">Rename</button>
            </div>
        </form>
    </div>
</div>

<div id="lightboxOverlay" class="lightbox-overlay" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img id="lightboxImage" src="" alt="Image marita">
</div>

<div id="moveModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Move File</h3>
        <form method="POST" action="favorites.php">
            <input type="hidden" name="action" value="move_to_folder">
            <input type="hidden" name="image_id" id="modal_image_id" value="">
            <select name="new_folder_id">
                <option value="">No Folder</option>
                <?php foreach ($foldere_utilizator as $f): ?>
                    <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="modal-actions">
                <button type="button" onclick="closeMoveModal()" class="btn-cancel-modal">Cancel</button>
                <button type="submit" class="btn-submit-modal">Move</button>
            </div>
        </form>
    </div>
</div>
<script>
        const themeCookieName = "<?php echo $cookie_name; ?>";
</script>
<script src="js/main.js"></script>

</body>
</html>