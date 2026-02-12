<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_auth.php';

$page_css = 'view_subscribers.css';

$subs = [];
$pdo = get_pdo();
if ($pdo) {
  $stmt = $pdo->query('SELECT email, subscribed_at FROM subscribers ORDER BY subscribed_at DESC');
  $subs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-subscribers-page">
  <div class="admin-subscribers-header">
    <div>
      <p class="admin-kicker">Communications</p>
      <h1>Subscribers</h1>
      <p class="admin-subtitle">Manage your email subscriber list.</p>
    </div>
    <div class="admin-subscribers-actions">
      <div class="admin-subscriber-count">
        <i class="bi bi-people"></i>
        <span><?= count($subs) ?> subscriber<?= count($subs) !== 1 ? 's' : '' ?></span>
      </div>
      <a class="btn btn-outline-secondary" href="dashboard.php">← Back to Dashboard</a>
    </div>
  </div>

  <?php if (empty($subs)): ?>
    <div class="admin-empty">
      <div class="admin-empty-icon"><i class="bi bi-envelope"></i></div>
      <p>No subscribers yet.</p>
      <small>Email subscribers will appear here once they sign up from your store.</small>
    </div>
  <?php else: ?>
    <div class="admin-subscribers-table-wrap">
      <table class="table admin-subscribers-table align-middle">
        <thead>
          <tr>
            <th class="admin-table-number">#</th>
            <th>Email Address</th>
            <th>Subscribed On</th>
            <th class="admin-table-action"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($subs as $idx => $s): ?>
            <tr>
              <td class="admin-table-number">
                <span class="admin-subscriber-badge"><?= $idx + 1 ?></span>
              </td>
              <td>
                <div class="admin-subscriber-email">
                  <i class="bi bi-envelope-circle"></i>
                  <span><?= htmlspecialchars($s['email'] ?? '') ?></span>
                </div>
              </td>
              <td class="admin-table-date">
                <?php 
                  $dateStr = $s['subscribed_at'] ?? '';
                  $date = strtotime($dateStr);
                  echo $date ? date('M j, Y', $date) : 'Unknown';
                ?>
              </td>
              <td class="admin-table-action">
                <a href="mailto:<?= htmlspecialchars($s['email'] ?? '') ?>" class="admin-action-link-text" title="Send email">
                  <i class="bi bi-envelope"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
