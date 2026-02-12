
<?php
require_once __DIR__ . '/includes/functions.php';
$items = cart_items();
if (empty($items)) {
  header('Location: cart.php');
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Store order in session for reference
  $_SESSION['last_order'] = [
    'items' => $items,
    'total' => cart_total(),
    'name' => $_POST['name'] ?? '',
    'email' => $_POST['email'] ?? '',
  ];
  // Prepare payment redirect with HMAC hash
  $amount = cart_total();
  $user_name = $_POST['name'] ?? '';
  $user_email = $_POST['email'] ?? '';
  // Load secret key from config.php (add 'bmipay_secret' => 'YOUR_SECRET_KEY', to your config)
  $config = require __DIR__ . '/config.php';
  $secret = $config['bmipay_secret'] ?? 'changeme';
  $data = $user_name . '|' . $user_email . '|' . $amount;
  $hash = hash_hmac('sha256', $data, $secret);
  $bmipay_url = "http://localhost/BMIPAY/index.php?user_name=" . urlencode($user_name) . "&user_email=" . urlencode($user_email) . "&amount={$amount}&hash={$hash}";
  unset($_SESSION['cart']);
  header('Location: ' . $bmipay_url);
  exit;
}
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>


<div class="checkout-bg py-5">
  <div class="checkout-container container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8">
        <div class="checkout-card shadow-lg">
          <h1 class="checkout-title mb-4">Checkout</h1>
          <form method="post" class="checkout-form row g-4">
            <div class="col-md-6">
              <label class="form-label">Full name</label>
              <input name="name" required class="form-control checkout-input">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input name="email" type="email" required class="form-control checkout-input">
            </div>
            <div class="col-12">
              <div class="checkout-summary p-3 mb-3 rounded">
                <h5 class="mb-3">Order Summary</h5>
                <ul class="checkout-items list-unstyled mb-2">
                  <?php foreach ($items as $it): $b = $it['book']; ?>
                  <li class="d-flex justify-content-between align-items-center mb-2">
                    <span><?= htmlspecialchars($b['title']) ?> <span class="text-muted small">x<?= $it['qty'] ?></span></span>
                    <span><?= format_price($b['price'] * $it['qty']) ?></span>
                  </li>
                  <?php endforeach; ?>
                </ul>
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                  <strong>Total</strong>
                  <strong><?= format_price(cart_total()) ?></strong>
                </div>
              </div>
            </div>
            <div class="col-12 d-flex flex-column flex-md-row gap-3">
              <button class="btn btn-gradient checkout-btn flex-fill" type="submit">Place Order</button>
              <a class="btn btn-secondary flex-fill" href="cart.php">Back to cart</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
