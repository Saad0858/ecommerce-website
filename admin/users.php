<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/get_users.php';

require_admin();
?>
<div class="admin-container">
    <div class="admin-header">
        <h2>User Management</h2>
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Search users...">
            <button type="submit" class="btn">Search</button>
        </form>
    </div>
    
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= $user['is_admin'] ? 'Admin' : 'Customer' ?></td>
            <td><?= $user['is_active'] ? 'Active' : 'Inactive' ?></td>
            <td>
                <a href="user_edit.php?id=<?= $user['id'] ?>" class="btn-sm">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>