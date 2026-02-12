<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_auth.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (!$new) {
        $error = 'Password cannot be empty.';
    } elseif ($new !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($new) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $current = isset($_SESSION['admin_user']) ? $_SESSION['admin_user'] : 'admin';
        $pdo = get_pdo();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE username = ?');
                $stmt->execute([$hash, $current]);
                $success = true;
            } catch (Exception $e) {
                $error = 'Database error. Please try again.';
            }
        } else {
            $error = 'Database connection is unavailable.';
        }
    }
}

$page_css = 'change_password.css';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="admin-password-page">
  <div class="admin-password-card">
    <div class="admin-password-header">
      <div>
        <p class="admin-kicker">Security</p>
        <h1>Change Password</h1>
        <p class="admin-password-subtitle">Update your admin account password</p>
      </div>
      <a href="dashboard.php" class="admin-btn admin-btn-secondary">← Back to Dashboard</a>
    </div>

    <?php if ($success): ?>
      <div class="admin-alert admin-alert-success">
        ✓ Password updated successfully!
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="admin-alert admin-alert-error">
        ✕ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <div class="admin-password-form-wrapper">
      <form method="post" class="admin-password-form">
        <div class="form-section">
          <div class="admin-field">
            <label for="new_password" class="form-label">New Password *</label>
            <input 
              id="new_password" 
              name="new_password" 
              type="password" 
              class="form-control" 
              autocomplete="new-password"
              required
              minlength="8"
              placeholder="Enter new password (min 8 characters)">
            <small class="admin-help">Must be at least 8 characters long.</small>
          </div>

          <div class="admin-field">
            <label for="confirm_password" class="form-label">Confirm Password *</label>
            <input 
              id="confirm_password" 
              name="confirm_password" 
              type="password" 
              class="form-control" 
              autocomplete="new-password"
              required
              minlength="8"
              placeholder="Re-enter password to confirm">
            <small class="admin-help">Must match the password above.</small>
          </div>
        </div>

        <div class="admin-actions">
          <button class="btn btn-primary" type="submit">Update Password</button>
          <a class="btn btn-outline-secondary" href="dashboard.php">Cancel</a>
        </div>
      </form>

      <div class="admin-password-tips">
        <h4>Password Security Tips</h4>
        <ul>
          <li>Use a mix of uppercase and lowercase letters</li>
          <li>Include numbers and special characters</li>
          <li>Avoid using common words or personal information</li>
          <li>Keep your password unique and memorable only to you</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
