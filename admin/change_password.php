<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (!$new || $new !== $confirm) {
        $error = 'Passwords do not match or are empty.';
    } else {
    $hash = password_hash($new, PASSWORD_DEFAULT);
    $current = isset($CURRENT_ADMIN) ? $CURRENT_ADMIN : (isset($cfg['admin']['user']) ? $cfg['admin']['user'] : 'admin');
    $pdo = get_pdo();
    if ($pdo) {
      $stmt = $pdo->prepare('UPDATE admins SET password_hash = ? WHERE username = ?');
      $stmt->execute([$hash, $current]);
      $success = true;
    } else {
      $error = 'Database connection is unavailable.';
    }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
  <h1>Change Admin Password</h1>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="alert alert-success">Password updated.</div><?php endif; ?>
  <form method="post" class="w-100" style="max-width:420px">
    <div class="mb-3"><label class="form-label">New password</label><input name="new_password" type="password" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Confirm password</label><input name="confirm_password" type="password" class="form-control" required></div>
    <div><button class="btn btn-primary">Save</button> <a class="btn btn-secondary" href="dashboard.php">Cancel</a></div>
  </form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
