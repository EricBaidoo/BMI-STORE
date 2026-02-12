<?php
require_once __DIR__ . '/../includes/functions.php';
$cfg = require __DIR__ . '/../config.php';

$error = null;
// Support upgraded hashed credentials stored in data/admin_credentials.php
$credsFile = __DIR__ . '/../data/admin_credentials.php';
$stored = $cfg['admin'] ?? [];
if (file_exists($credsFile)) {
  $c = require $credsFile; if (is_array($c)) $stored = $c;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['username'] ?? '';
  $p = $_POST['password'] ?? '';
  $expectedUser = $stored['user'] ?? ($cfg['admin']['user'] ?? 'admin');
  $hash = $stored['pass_hash'] ?? null;
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

  if (!$dbChecked && $u === $expectedUser) {
    if ($hash && password_verify($p, $hash)) {
      $ok = true;
    } elseif ($legacyPass && $p === $legacyPass) {
      // legacy plaintext match - upgrade to hashed credentials file
      $newHash = password_hash($p, PASSWORD_DEFAULT);
      $cred = [ 'user' => $expectedUser, 'pass_hash' => $newHash ];
      file_put_contents(__DIR__ . '/../data/admin_credentials.php', "<?php\nreturn " . var_export($cred, true) . ";\n");
      $ok = true;
    }
  }

  if ($ok) {
    $_SESSION['is_admin'] = true;
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
        
        <?php if ($error): ?>
          <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" class="admin-login-form">
          <div class="mb-3">
            <label class="form-label" for="admin-username">Username</label>
            <input id="admin-username" name="username" class="form-control" required autocomplete="username">
          </div>
          <div class="mb-3">
            <label class="form-label" for="admin-password">Password</label>
            <input id="admin-password" name="password" type="password" class="form-control" required autocomplete="current-password">
          </div>
          <button class="btn btn-primary w-100" type="submit">Sign in</button>
          <div class="admin-login-footer">
            <a class="btn btn-link" href="../index.php">Back to store</a>
          </div>
        </form>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
