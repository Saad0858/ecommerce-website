<?php
require_once __DIR__ . '/../backend/includes/config.php';
require_once __DIR__ . '/../backend/includes/auth_check.php';
require_once __DIR__ . '/../backend/includes/db_connection.php';

require_admin();

$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($user_id) {
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $is_active = filter_input(INPUT_POST, 'is_active', FILTER_VALIDATE_BOOLEAN);
    $is_admin = filter_input(INPUT_POST, 'is_admin', FILTER_VALIDATE_BOOLEAN);
    
    $stmt = $mysqli->prepare("UPDATE users SET name = ?, email = ?, is_active = ?, is_admin = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $name, $email, $is_active, $is_admin, $user_id);
    $stmt->execute();
    
    header("Location: users.php");
    exit;
}
?>
<form method="POST">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">
    </div>
    
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_active" <?= $user['is_active'] ? 'checked' : '' ?>>
            Active
        </label>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?>>
            Administrator
        </label>
    </div>
    
    <button type="submit" class="btn">Save Changes</button>
</form>