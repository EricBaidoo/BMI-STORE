<?php
require_once __DIR__ . '/../includes/functions.php';
// Protect admin pages
require_once __DIR__ . '/_auth.php';
$page_css = 'dashboard.css';

$books = all_books();
$bookCount = is_array($books) ? count($books) : 0;

$orders = [];
if (isset($_SESSION['last_order'])) $orders[] = $_SESSION['last_order'];
$orderCount = count($orders);

$subsFile = __DIR__ . '/../data/subscribers.json';
$subs = [];
if (file_exists($subsFile)) {
  $data = json_decode(file_get_contents($subsFile), true);
  if (is_array($data)) $subs = $data;
}
$subCount = count($subs);
$recentSub = $subCount ? $subs[$subCount - 1] : null;
$recentOrder = $orderCount ? $orders[0] : null;

require_once __DIR__ . '/../includes/header.php';
?>
  <section class="admin-dashboard">
    <div class="admin-hero">
      <div>
        <p class="admin-kicker">Admin Dashboard</p>
        <h1>Welcome back</h1>
        <p class="admin-subtitle">Track your store health, update inventory, and manage subscribers.</p>
      </div>
      <div class="admin-hero-actions">
        <a class="btn btn-primary" href="add_book.php"><i class="bi bi-plus-lg"></i> Add book</a>
        <a class="btn btn-outline-secondary" href="view_orders.php">View orders</a>
      </div>
    </div>

    <div class="admin-stats">
      <div class="admin-stat-card">
        <div class="admin-stat-icon"><i class="bi bi-book"></i></div>
        <div>
          <p>Books in catalog</p>
          <h3><?= $bookCount ?></h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="admin-stat-icon"><i class="bi bi-bag-check"></i></div>
        <div>
          <p>Orders recorded</p>
          <h3><?= $orderCount ?></h3>
        </div>
      </div>
      <div class="admin-stat-card">
        <div class="admin-stat-icon"><i class="bi bi-people"></i></div>
        <div>
          <p>Subscribers</p>
          <h3><?= $subCount ?></h3>
        </div>
      </div>
    </div>

    <div class="admin-panels">
      <div class="admin-card">
        <h4>Quick actions</h4>
        <div class="admin-action-list">
          <a href="add_book.php"><i class="bi bi-journal-plus"></i> Add new book</a>
          <a href="view_books.php"><i class="bi bi-collection"></i> View all books</a>
          <a href="view_orders.php"><i class="bi bi-receipt"></i> Review latest orders</a>
          <a href="view_subscribers.php"><i class="bi bi-envelope-check"></i> View subscribers</a>
          <a href="change_password.php"><i class="bi bi-shield-lock"></i> Change admin password</a>
        </div>
      </div>
      <div class="admin-card">
        <h4>Recent activity</h4>
        <div class="admin-activity">
          <div class="admin-activity-item">
            <span>Last order</span>
            <?php if ($recentOrder): ?>
              <strong><?= htmlspecialchars($recentOrder['name'] ?? 'Guest') ?> · <?= format_price($recentOrder['total'] ?? 0) ?></strong>
            <?php else: ?>
              <strong>No orders yet</strong>
            <?php endif; ?>
          </div>
          <div class="admin-activity-item">
            <span>Newest subscriber</span>
            <?php if ($recentSub): ?>
              <strong><?= htmlspecialchars($recentSub['email'] ?? '') ?></strong>
            <?php else: ?>
              <strong>No subscribers yet</strong>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

  </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
