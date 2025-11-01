<?php
require_once __DIR__ . '/includes/functions.php';
// Retrieve last order from session
$order = $_SESSION['last_order'] ?? null;
if (!$order) {
    header('Location: cart.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate payment success
    unset($_SESSION['last_order']);
    header('Location: success.php?paid=1');
    exit;
}
require_once __DIR__ . '/includes/header.php';
?>
<div class="checkout-bg py-5">
  <div class="checkout-container container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-7">
        <div class="checkout-card shadow-lg">
          <h1 class="checkout-title mb-4">Payment</h1>
          <div class="mb-4">
            <h5 class="mb-3">Order Summary</h5>
            <ul class="checkout-items list-unstyled mb-2">
              <?php foreach ($order['items'] as $it): $b = $it['book']; ?>
              <li class="d-flex justify-content-between align-items-center mb-2">
                <span><?= htmlspecialchars($b['title']) ?> <span class="text-muted small">x<?= $it['qty'] ?></span></span>
                <span><?= format_price($b['price'] * $it['qty']) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
              <strong>Total</strong>
              <strong><?= format_price($order['total']) ?></strong>
            </div>
          </div>
          <form method="post" class="row g-4">
            <div class="col-12">
              <label class="form-label">Card Number</label>
              <input type="text" class="form-control checkout-input" placeholder="1234 5678 9012 3456" maxlength="19" required>
            </div>
            <div class="col-6">
              <label class="form-label">Expiry</label>
              <input type="text" class="form-control checkout-input" placeholder="MM/YY" maxlength="5" required>
            </div>
            <div class="col-6">
              <label class="form-label">CVV</label>
              <input type="text" class="form-control checkout-input" placeholder="123" maxlength="4" required>
            </div>
            <div class="col-12 d-flex flex-column flex-md-row gap-3">
              <button class="btn btn-gradient checkout-btn flex-fill" type="submit">Pay Now</button>
              <a class="btn btn-secondary flex-fill" href="cart.php">Back to cart</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
