<?php
require_once __DIR__ . '/../includes/functions.php';
// Protect admin pages
require_once __DIR__ . '/_auth.php';

$page_css = 'view_orders.css';

$orders = [];
if (isset($_SESSION['last_order'])) $orders[] = $_SESSION['last_order'];

require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-orders-page">
  <div class="admin-orders-header">
    <div>
      <p class="admin-kicker">Sales</p>
      <h1>Orders</h1>
      <p class="admin-subtitle">View and track customer orders.</p>
    </div>
    <div class="admin-orders-actions">
      <a class="btn btn-outline-secondary" href="dashboard.php">← Back to Dashboard</a>
    </div>
  </div>

  <?php if (empty($orders)): ?>
    <div class="admin-empty">
      <div class="admin-empty-icon"><i class="bi bi-inbox"></i></div>
      <p>No orders placed yet.</p>
      <small>Orders will appear here once customers complete their purchases.</small>
    </div>
  <?php else: ?>
    <div class="admin-orders-grid">
      <?php foreach ($orders as $o): ?>
        <div class="admin-order-card">
          <div class="admin-order-header">
            <div>
              <h4><?= htmlspecialchars($o['name'] ?? 'Guest') ?></h4>
              <p class="admin-order-time"><?= htmlspecialchars($o['date'] ?? date('M j, Y')) ?></p>
            </div>
            <div class="admin-order-total">
              <?= format_price($o['total'] ?? 0) ?>
            </div>
          </div>

          <div class="admin-order-items">
            <p class="admin-order-items-label">Items ordered:</p>
            <ul class="admin-order-list">
              <?php foreach ($o['items'] as $it): ?>
                <li>
                  <span class="admin-order-item-title">
                    <?= htmlspecialchars($it['book']['title'] ?? 'Unknown Book') ?>
                  </span>
                  <span class="admin-order-item-qty">
                    ×<?= (int)$it['qty'] ?>
                  </span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div class="admin-order-footer">
            <p class="admin-order-status">Status: <strong>Completed</strong></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
