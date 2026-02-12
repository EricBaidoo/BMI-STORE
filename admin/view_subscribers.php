<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../includes/header.php';

$subs = [];
$pdo = get_pdo();
if ($pdo) {
  $stmt = $pdo->query('SELECT email, subscribed_at FROM subscribers ORDER BY subscribed_at DESC');
  $subs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
  <h1>Subscribers</h1>
  <?php if (empty($subs)): ?>
    <p>No subscribers yet.</p>
  <?php else: ?>
    <table class="table table-striped">
      <thead><tr><th>#</th><th>Email</th><th>Subscribed At</th></tr></thead>
      <tbody>
      <?php foreach ($subs as $i => $s): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= htmlspecialchars($s['email'] ?? '') ?></td>
          <td><?= htmlspecialchars($s['subscribed_at'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <a class="btn btn-secondary" href="dashboard.php">Back</a>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
