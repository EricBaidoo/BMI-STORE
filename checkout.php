<?php
require_once __DIR__ . '/includes/functions.php';
$page_css = 'checkout.css';

$items = cart_items();
if (empty($items)) {
  header('Location: cart.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $_SESSION['last_order'] = [
    'items' => $items,
    'total' => cart_total(),
    'name' => $_POST['name'] ?? '',
    'email' => $_POST['email'] ?? '',
  ];
  
  $amount = cart_total();
  $user_name = $_POST['name'] ?? '';
  $user_email = $_POST['email'] ?? '';
  
  $config = require __DIR__ . '/config.php';
  $secret = $config['bmipay_secret'] ?? 'changeme';
  $data = $user_name . '|' . $user_email . '|' . $amount;
  $hash = hash_hmac('sha256', $data, $secret);
  
  $bmipay_url = "http://localhost/BMIPAY/index.php?user_name=" . urlencode($user_name) . "&user_email=" . urlencode($user_email) . "&amount={$amount}&hash={$hash}";
  
  unset($_SESSION['cart']);
  header('Location: ' . $bmipay_url);
  exit;
}

$fallbackCover = 'assets/images/books%20main.png';

require_once __DIR__ . '/includes/header.php';
?>

<main class="checkout-page">
  <section class="checkout-header">
    <div class="container">
      <h1>Checkout</h1>
    </div>
  </section>

  <section class="checkout-content">
    <div class="container">
      <div class="checkout-grid">
        <div class="checkout-form-section">
          <div class="checkout-card">
            <h2>Billing Information</h2>
            <form method="post" class="checkout-form">
              <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
              </div>
              <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="you@example.com">
              </div>
              
              <div class="checkout-actions">
                <button type="submit" class="btn checkout-submit-btn">Place Order</button>
                <a href="cart.php" class="btn checkout-back-btn">Back to Cart</a>
              </div>
            </form>
          </div>
        </div>

        <div class="checkout-summary-section">
          <div class="checkout-summary">
            <h2>Order Summary</h2>
            <div class="checkout-items">
              <?php foreach ($items as $it): 
                $b = $it['book'];
                $cover = !empty($b['cover']) ? $b['cover'] : $fallbackCover;
                $coverUrl = resolve_cover_url($cover);
              ?>
                <div class="checkout-item">
                  <div class="checkout-item-image">
                    <img src="<?= htmlspecialchars($coverUrl) ?>" alt="<?= htmlspecialchars($b['title']) ?>">
                  </div>
                  <div class="checkout-item-details">
                    <p class="checkout-item-title"><?= htmlspecialchars($b['title']) ?></p>
                    <p class="checkout-item-qty">Qty: <?= $it['qty'] ?></p>
                  </div>
                  <div class="checkout-item-price">
                    <?= format_price($b['price'] * $it['qty']) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="checkout-summary-total">
              <div class="checkout-summary-row">
                <span>Subtotal (<?= count($items) ?> <?= count($items) === 1 ? 'item' : 'items' ?>):</span>
                <span><?= format_price(cart_total()) ?></span>
              </div>
              <div class="checkout-summary-grand">
                <span>Order Total:</span>
                <span><?= format_price(cart_total()) ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
