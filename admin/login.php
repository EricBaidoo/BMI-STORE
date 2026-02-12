<?php
require_once __DIR__ . '/../includes/functions.php';
$cfg = require __DIR__ . '/../config.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';
  $expectedUser = $cfg['admin']['user'] ?? 'admin';
  $legacyPass = $cfg['admin']['pass'] ?? null;

  $ok = false;
  $dbChecked = false;
  $pdo = get_pdo();
  if ($pdo) {
    $dbChecked = true;
    try {
      $stmt = $pdo->prepare('SELECT id, password_hash FROM admins WHERE username = ? LIMIT 1');
      $stmt->execute([$u]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($row && password_verify($p, $row['password_hash'])) {
        $ok = true;
      } elseif (!$row && $u === $expectedUser) {
        $seedHash = null;
        if ($hash && password_verify($p, $hash)) {
          $seedHash = $hash;
        } elseif ($legacyPass && $p === $legacyPass) {
          $seedHash = password_hash($p, PASSWORD_DEFAULT);
        }

        if ($seedHash) {
          $ins = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
          $ins->execute([$expectedUser, $seedHash]);
          $ok = true;
        }
      }
    } catch (Exception $e) {
      $dbChecked = false;
    }
  }

  if (!$dbChecked && $u === $expectedUser && $legacyPass && $p === $legacyPass) {
    $ok = true;
  }

  if ($ok) {
    $_SESSION['is_admin'] = true;
    $_SESSION['admin_user'] = $u;
    header('Location: dashboard.php'); exit;
  }
  $error = 'Invalid credentials';
}

$page_css = 'login.css';
require_once __DIR__ . '/../includes/header.php';
?>
  <section class="admin-login">
    <div class="admin-login-card">
      <div class="admin-login-visual" aria-hidden="true">
        <div class="admin-login-overlay">
          <div class="admin-login-brand">
            <span class="admin-login-brand-title">BMI Book Store</span>
            <span class="admin-login-brand-sub">Admin Login</span>
          </div>
        </div>
      </div>
      <div class="admin-login-panel">
        <div class="admin-login-inner">
          <div class="admin-login-title-section">
            <h2>Admin Access</h2>
            <p>Sign in to your admin account</p>
          </div>

          <?php if ($error): ?>
            <div class="admin-login-alert admin-login-alert-error">
              <i class="bi bi-exclamation-circle"></i>
              <span><?= htmlspecialchars($error) ?></span>
            </div>
          <?php endif; ?>

          <form method="post" class="admin-login-form">
            <div class="admin-login-field">
              <label for="admin-username" class="admin-login-label">Username</label>
              <input 
                id="admin-username" 
                name="username" 
                class="admin-login-input" 
                required 
                autocomplete="username"
                placeholder="Enter your username">
            </div>

            <div class="admin-login-field">
              <label for="admin-password" class="admin-login-label">Password</label>
              <input 
                id="admin-password" 
                name="password" 
                type="password" 
                class="admin-login-input" 
                required 
                autocomplete="current-password"
                placeholder="Enter your password">
            </div>

            <button class="admin-login-submit" type="submit">
              <span>Sign In</span>
              <i class="bi bi-arrow-right"></i>
            </button>

            <div class="admin-login-divider">
              <span>Need help?</span>
            </div>

            <a class="admin-login-back-link" href="../index.php">
              <i class="bi bi-house"></i>
              <span>Back to Store</span>
            </a>
          </form>
        </div>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
