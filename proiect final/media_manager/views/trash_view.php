<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Bin</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/trash_style.css">
</head>
<body class="view-trash <?php echo ($theme === 'dark') ? 'dark-mode' : ''; ?>">

<input type="checkbox" id="mobile-menu-toggle" style="display: none;">
<input type="checkbox" id="settings-menu-toggle" style="display: none;">

<aside class="sidebar">
    <div class="brand">Momento</div>
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-item"><span>🖥</span> My Files</a>
        <a href="folders.php" class="nav-item"><span>📁</span> Folders</a>
        <a href="favorites.php" class="nav-item"><span>☆</span> Favorite</a>
        <a href="trash.php" class="nav-item active"><span>🗑</span> Trash</a>
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
        
            <h2 class="trash-title">Recycle Bin</h2>
            <div class="gallery">
                <?php if (empty($images)): ?>
                    <p class="empty-state-text">The recycle bin is empty.</p>
                <?php else: ?>
                    <?php foreach ($images as $img): ?>
                        <div class="image-card">
                            <img src="uploads/<?php echo htmlspecialchars($img['filename']); ?>" onclick="openLightbox('uploads/<?php echo htmlspecialchars($img['filename']); ?>')">
                            <div class="image-info trash-image-info">
                                <div class="trash-image-details">
                                    <span class="image-title"><?php echo htmlspecialchars($img['title']); ?></span>
                                    <div class="trash-image-size"><?php echo $img['size_mb']; ?> MB</div>
                                </div>
                                <div class="action-group trash-action-group">
                                    <form method="POST" class="inline-form"><input type="hidden" name="action" value="restore"><input type="hidden" name="image_id" value="<?php echo $img['id']; ?>"><button class="btn-restore-action" title="Restore">⟲ Restore</button></form>
                                    <button type="button" class="btn-del-action" onclick="openPermanentDeleteModal(<?php echo $img['id']; ?>)" title="Delete Forever">x Delete</button>
                                </div>
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

    <!-- grafic stocare pentru gunoi -->
    <div>
        <div class="storage-chart-container">
            <div class="donut-chart dynamic-donut" style="--bg-grad: conic-gradient(#f59e0b <?php echo $pct_trash; ?>%, #f1f5f9 <?php echo $pct_trash; ?>%);">
                <div class="inner-circle">
                    <span class="storage-value trash-storage-value"><?php echo number_format($trash_used, 1); ?> MB</span>
                    <span class="trash-storage-label">in Recycle Bin</span>
                </div>
            </div>
        </div>
        <div class="trash-storage-summary">
            <span class="trash-percentage-highlight"><?php echo number_format($pct_trash, 1); ?>%</span> of Total Quota
            <?php if ($pct_total >= 90): ?>
                <div class="alert-danger-box">
                    ⚠️ Storage is almost full (<?php echo number_format($pct_total, 1); ?>%)! <br>Empty the bin to free up space.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="widget-card trash-empty-widget">
        <button type="button" class="btn-empty-trash" <?php echo empty($images) ? 'disabled class="disabled-btn"' : ''; ?> onclick="openEmptyTrashModal()">Empty Trash</button>
    </div>
</aside>

    </div>
</div>

<div id="lightboxOverlay" class="lightbox-overlay" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img id="lightboxImage" src="" alt="Image marita">
</div>


<!-- modale -->
<div id="permDeleteModal" class="modal-overlay">
    <div class="modal-content text-center modal-content--center">
        <div class="modal-icon-danger">!</div>
        <h3 class="margin-0">Delete Permanently</h3>
        <p class="modal-text-muted">Are you sure? This file will be permanently removed.</p>
        <form method="POST" action="trash.php">
            <input type="hidden" name="action" value="delete_permanent">
            <input type="hidden" name="image_id" id="perm_delete_id" value="">
            <div class="modal-actions justify-center modal-actions--center">
                <button type="button" onclick="closePermanentDeleteModal()" class="modal-btn modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<div id="emptyTrashModal" class="modal-overlay">
    <div class="modal-content text-center modal-content--center">
        <div class="modal-icon-danger">!</div>
        <h3 class="margin-0">Empty Trash</h3>
        <p class="modal-text-muted">Are you sure you want to empty the trash? All files will be permanently removed.</p>
        <form method="POST" action="trash.php">
            <input type="hidden" name="action" value="empty_trash">
            <div class="modal-actions justify-center modal-actions--center">
                <button type="button" onclick="closeEmptyTrashModal()" class="modal-btn modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">Empty Trash</button>
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