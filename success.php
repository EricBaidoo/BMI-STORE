<?php
require_once __DIR__ . '/includes/functions.php';
$order = $_SESSION['last_order'] ?? null;
if (!$order) {
    header('Location: books.php');
    exit;
}
require_once __DIR__ . '/includes/header.php';
?>
  <h1>Thank you, <?= htmlspecialchars($order['name'] ?? '') ?>!</h1>
  <p>Your order has been placed. A confirmation was (mock) sent to <strong><?= htmlspecialchars($order['email'] ?? '') ?></strong>.</p>
  <h3>Order summary</h3>
  <ul>
    <?php foreach ($order['items'] as $it): ?>
      <li><?= htmlspecialchars($it['book']['title']) ?> x <?= $it['qty'] ?> — <?= format_price($it['book']['price'] * $it['qty']) ?></li>
    <?php endforeach; ?>
  </ul>
  <p><strong>Total: <?= format_price($order['total']) ?></strong></p>
  <a class="btn btn-primary" href="books.php">Continue shopping</a>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
