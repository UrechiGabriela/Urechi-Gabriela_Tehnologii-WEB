<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Folders</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/folders_style.css">
    </head>
<body class="view-folders <?php echo ($theme === 'dark') ? 'dark-mode' : ''; ?>">

<input type="checkbox" id="mobile-menu-toggle" style="display: none;">
<input type="checkbox" id="settings-menu-toggle" style="display: none;">

<aside class="sidebar">
    <div class="brand">Momento</div>
    <nav class="nav-menu">
        <a href="dashboard.php" class="nav-item"><span>🖥</span> My Files</a>
        <a href="folders.php" class="nav-item active"><span>📁</span> Folders</a>
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
            <div class="page-header">
                <h2 class="page-title-text">My Folders</h2>
            </div>

            <div class="folders-grid">
                <?php if (empty($folders)): ?>
                    <p class="empty-state-text">You don't have any folders yet. Create one from the right panel.</p>
                <?php else: ?>
                    <?php foreach ($folders as $folder): ?>
                        <a href="dashboard.php?folder_id=<?php echo $folder['id']; ?>" class="folder-card ">
                            <!-- Iconiță Text mare pentru folder -->
                            <div class="folder-icon">📁</div>
                            
                            <div class="folder-name"><?php echo htmlspecialchars($folder['name']); ?></div>
                            <div class="folder-date">Created: <?php echo date('d M Y', strtotime($folder['created_at'])); ?></div>
                        </a>
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

    <div class="upload-card upload-card--blue">
        <h3 class="widget-title text-blue">Create Folder</h3>
        <?php if (!empty($mesaj)) echo "<div style='color:#ef4444; font-size:13px; margin-bottom:10px; font-weight:600;'>$mesaj</div>"; ?>
        <form action="folders.php" method="POST">
            <input type="hidden" name="action" value="create_folder">
            <input type="text" name="folder_name" placeholder="Folder Name" required class="form-input-box">
            <button type="submit" class="btn-upload btn-action--blue ">Create New</button>
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

<script>
        const themeCookieName = "<?php echo $cookie_name; ?>";
</script>
<script src="js/main.js"></script>
</body>
</html>