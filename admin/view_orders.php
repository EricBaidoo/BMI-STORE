<?php
require_once __DIR__ . '/../includes/functions.php';
// Protect admin pages
require_once __DIR__ . '/_auth.php';
$orders = [];
if (isset($_SESSION['last_order'])) $orders[] = $_SESSION['last_order'];
require_once __DIR__ . '/../includes/header.php';
?>
  <h1>Orders</h1>
  <?php if (empty($orders)): ?>
    <p>No orders yet.</p>
  <?php else: ?>
    <?php foreach ($orders as $o): ?>
      <div class="card mb-3"><div class="card-body">
        <h5><?= htmlspecialchars($o['name'] ?? 'Guest') ?> — <?= format_price($o['total']) ?></h5>
        <ul>
        <?php foreach ($o['items'] as $it): ?>
          <li><?= htmlspecialchars($it['book']['title']) ?> x <?= $it['qty'] ?></li>
        <?php endforeach; ?>
        </ul>
      </div></div>
    <?php endforeach; ?>
  <?php endif; ?>
  <a class="btn btn-secondary" href="dashboard.php">Back</a>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
