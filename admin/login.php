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

  $ok = false;
  if ($u === $expectedUser) {
    if ($hash && password_verify($p, $hash)) {
      $ok = true;
    } elseif (isset($cfg['admin']['pass']) && $p === $cfg['admin']['pass']) {
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

require_once __DIR__ . '/../includes/header.php';
?>
  <h1>Admin Login</h1>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" class="w-100" style="max-width:420px">
    <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Password</label><input name="password" type="password" class="form-control" required></div>
    <div><button class="btn btn-primary">Sign in</button> <a class="btn btn-secondary" href="../index.php">Back</a></div>
  </form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
