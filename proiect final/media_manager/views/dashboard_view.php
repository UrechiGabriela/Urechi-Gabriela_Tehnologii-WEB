<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Momento</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/dashboard_style.css">
    </head>
<body class="view-dashboard <?php echo ($theme === 'dark') ? 'dark-mode' : ''; ?>">

<input type="checkbox" id="mobile-menu-toggle" style="display: none;">
<input type="checkbox" id="settings-menu-toggle" style="display: none;">

<aside class="sidebar">
    <div class="brand">Momento</div>
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-item <?php echo !$current_folder_id ? 'active' : ''; ?>"><span>🖥</span> My Files</a>
        <a href="folders.php" class="nav-item <?php echo $current_folder_id ? 'active' : ''; ?>"><span>📁</span> Folders</a>
        <a href="favorites.php" class="nav-item"><span>☆</span> Favorite</a>
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
        <header class="header-content">
            <div class="greeting-section">
                <h1 class="greeting-title">Welcome, <?php echo explode('@', htmlspecialchars($user_email))[0]; ?>!</h1>
                <p>Capture the moment, we’ll handle the storage.</p>
            </div>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search file...">
            </div>
        </header>
            <div class="page-header">
                <h2 class="page-title-text">
                    <?php echo $current_folder_id ? "Folder: " . htmlspecialchars($folder_rez['name'] ?? '') : "All Files"; ?>
                </h2>
                <?php if ($current_folder_id): ?>
                    <div class="header-actions">
                        <a href="folders.php" class="btn-back">⬅ Back to folders</a>
                        <button type="button" class="btn-back btn-back--danger" onclick="openDeleteFolderModal(<?php echo $current_folder_id; ?>)">Delete Folder</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="gallery" id="galleryContainer">
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
                                    <button type="button" class="action-btn" onclick="openMoveModal(<?php echo $img['id']; ?>)">⚲</button>
                                    <form method="POST" action="dashboard.php<?php echo $current_folder_id ? '?folder_id=' . $current_folder_id : ''; ?>" class="inline-form">
                                        <input type="hidden" name="action" value="toggle_favorite">
                                        <input type="hidden" name="image_id" value="<?php echo $img['id']; ?>">
                                        <button type="submit" class="action-btn <?php echo $img['is_favorite'] == 1 ? 'fav-active' : ''; ?>">★</button>
                                    </form>
                                    <button type="button" class="action-btn btn-del inline-form" onclick="openDeleteFileModal(<?php echo $img['id']; ?>)">x</button>
                                </div>
                            </div>
                            <span class="image-date"><?php echo date('l, d M Y', strtotime($img['upload_date'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
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
    <div class="theme-toggle-container">
    <span class="theme-label">☀️</span>
    <label class="theme-switch">
        <input type="checkbox" id="theme-checkbox" onchange="toggleTheme()" <?php echo ($theme === 'dark') ? 'checked' : ''; ?>>
        <span class="slider round"></span>
    </label>
    <span class="theme-label">🌙</span>
</div>

    <div class="upload-card">
        <h3 class="widget-title text-accent">Upload Files</h3>
        <?php if (!empty($mesaj)) echo "<div style='color:#ef4444; font-size:13px; margin-bottom:10px; font-weight:600;'>$mesaj</div>"; ?>
        <form action="dashboard.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="titlu" placeholder="File Name" required>
            <input type="hidden" name="folder_id" value="<?php echo $current_folder_id ? $current_folder_id : ''; ?>">
            <label class="file-input-label">
                📁 Choose File
                <input type="file" name="imagine" required accept="image/png, image/jpeg, image/webp" style="display: none;">
            </label>
            <button type="submit" class="btn-upload">Upload Now</button>
        </form>
    </div>

    
    <?php if (($total_used + $trash_used) / $max_storage >= 0.9): ?>
    <div class="alert-warning">
        ⚠️ Warning: You have reached over <?php echo number_format((($total_used + $trash_used) / $max_storage) * 100, 0); ?>% of your storage capacity! To free up space, go to the Trash section and permanently delete files.
    </div>
    <?php endif; ?>
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

<div id="lightboxOverlay" class="lightbox-overlay" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img id="lightboxImage" src="" alt="Image marita">
</div>

<div id="renameModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Rename File</h3>
        <form method="POST" action="dashboard.php<?php echo $current_folder_id ? '?folder_id='.$current_folder_id : ''; ?>">
            <input type="hidden" name="action" value="edit_title">
            <input type="hidden" name="image_id" id="rename_image_id" value="">
            <input type="text" name="new_title" id="rename_new_title" class="form-input-text" required>
            <div class="modal-actions">
                <button type="button" onclick="closeRenameModal()" class=" modal-btn modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">Rename</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteFileModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-icon-danger">!</div>
        <h3 class="margin-0"><?php echo $current_folder_id ? 'Remove from Folder' : 'Delete File'; ?></h3>
        <p >
            <?php echo $current_folder_id
                ? 'Are you sure you want to remove this image from the folder? The image will stay in your files.'
                : 'Are you sure you want to move this file to trash?'; ?>
        </p>
        <form method="POST" action="dashboard.php<?php echo $current_folder_id ? '?folder_id='.$current_folder_id : ''; ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="image_id" id="delete_file_id" value="">
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteFileModal()" class="modal-btn modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger"><?php echo $current_folder_id ? 'Remove' : 'Delete'; ?></button>
            </div>
        </form>
    </div>
</div>

<div id="deleteFolderModal" class="modal-overlay">
    <div class="modal-content ">
        <div class="modal-icon-danger">!</div>
        <h3 class="margin-0">Delete Folder</h3>
        <p >Are you sure you want to delete this folder? Images inside will NOT be deleted.</p>
        <form method="POST" action="folders.php">
            <input type="hidden" name="action" value="delete_folder">
            <input type="hidden" name="folder_id" id="delete_folder_id" value="">
            <div class="modal-actions ">
                <button type="button" onclick="closeDeleteFolderModal()" class="modal-btn modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>
<div id="moveModal" class="modal-overlay">
    <div class="modal-content">
        <h3>Move File</h3>
        <form method="POST" action="dashboard.php<?php echo $current_folder_id ? '?folder_id=' . $current_folder_id : ''; ?>">
            <input type="hidden" name="action" value="move_to_folder">
            <input type="hidden" name="image_id" id="modal_image_id" value="">
            <select name="new_folder_id">
                <option value="">No Folder</option>
                <?php foreach ($foldere_utilizator as $f): ?>
                    <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="modal-actions">
                <button type="button" onclick="closeMoveModal()" class="modal-btn modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">Move</button>
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