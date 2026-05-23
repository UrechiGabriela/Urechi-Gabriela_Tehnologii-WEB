<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles/admin_style.css">
    </head>
<body>

<input type="checkbox" id="mobile-menu-toggle">
<label for="mobile-menu-toggle" class="mobile-menu-overlay"></label>
<label for="mobile-menu-toggle" class="hamburger-button">
    <span></span>
    <span></span>
    <span></span>
</label>

<aside class="sidebar">
    <div class="brand">Momento</div>
    <nav class="nav-menu">
        <a href="admin.php" class="nav-item active"><span>⚙️</span> Admin Panel</a>
    </nav>
</aside>

<main class="main-content">

    <div class="header-content">
        <h1 class="page-title">Admin Panel</h1>
        <div class="user-dropdown" tabindex="0">
            <div class="user-profile-right">
                <span class="user-avatar"><?php echo strtoupper(substr($user_email, 0, 1)); ?></span>
                <span><?php echo htmlspecialchars(explode('@', $user_email)[0]); ?> ⏷</span>
            </div>
            <div class="dropdown-content">
                <a href="logout.php">Log Out</a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-title">Total Users</span>
            <span class="stat-value"><?php echo number_format($stats['users']); ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Uploaded Images</span>
            <span class="stat-value"><?php echo number_format($stats['images']); ?></span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Total Used Space</span>
            <span class="stat-value"><?php echo number_format($stats['space'], 1) . " / " . number_format($stats['total_quota'], 0); ?> MB</span>
        </div>
    </div>

    <div class="users-table-container">
        <h3 style="margin-top:0; margin-bottom:20px;">User List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Files</th>
                    <th>Used Space</th>
                    <th>Storage Limit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td>#<?php echo $u['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($u['email']); ?></strong></td>
                    <td>
                        <span class="role-badge <?php echo $u['role'] === 'admin' ? 'role-admin' : 'role-user'; ?>">
                            <?php echo ucfirst($u['role']); ?>
                        </span>
                    </td>
                    <td><?php echo (int)$u['img_count']; ?></td>
                    <td><?php echo number_format($u['used_mb'] ?? 0, 1); ?> MB</td>
                    <td><?php echo number_format($u['max_storage_mb'], 0); ?> MB</td>
                    <td>
                        <div class="action-flex">

                        <form method="POST" class="add-gb-form" title="Add extra limit (MB)">
                                <input type="hidden" name="action" value="add_gb">
                                <input type="hidden" name="target_user_id" value="<?php echo $u['id']; ?>">
                                <input type="number" name="mb_amount" min="1" max="100000" placeholder="+MB" required>
                                <button type="submit" class="btn btn-add">Add</button>
                            </form>
                            
                            <?php if($u['id'] != $user_id): ?>
                            <button type="button" class="btn btn-danger" onclick="openDeleteUserModal(<?php echo $u['id']; ?>)">Șterge user</button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<div id="deleteUserModal" class="modal-overlay">
    <div class="modal-content">
        <div class="clean-style-105">!</div>
        <h3>Delete User</h3>
        <p>Are you sure you want to permanently delete this user? All their files will be lost.</p>
        <form method="POST" action="admin.php">
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" name="target_user_id" id="delete_user_id" value="">
            <div class="modal-actions">
                <button type="button" onclick="closeDeleteUserModal()" class="modal-btn modal-btn-cancel">Cancel</button>
                <button type="submit" class="modal-btn modal-btn-danger">Delete Forever</button>
            </div>
        </form>
    </div>
</div>

<script src="js/main.js"></script>
</body>
</html>