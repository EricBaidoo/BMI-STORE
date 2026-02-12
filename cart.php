<?php
require_once __DIR__ . '/includes/functions.php';
$page_css = 'cart.css';

$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST['qty'] ?? [] as $id => $q) {
    $q = max(0, (int)$q);
    if ($q === 0) {
      cart_remove($id);
    } else {
      $_SESSION['cart'][$id] = $q;
    }
  }
  
  if (!$is_ajax) {
    header('Location: cart.php');
    exit;
  }
}

$items = cart_items();
$fallbackCover = 'assets/images/books%20main.png';

require_once __DIR__ . '/includes/header.php';
?>

<main class="cart-page">
  <section class="cart-header">
    <div class="container">
      <h1>Shopping Cart</h1>
    </div>
  </section>

  <section class="cart-content">
    <div class="container">
      <?php if (empty($items)): ?>
        <div class="cart-empty">
          <div class="cart-empty-content">
            <i class="bi bi-cart-x"></i>
            <h2>Your cart is empty</h2>
            <p>Browse our collection and add some books to your cart.</p>
            <a href="books.php" class="btn cart-continue-btn">Browse Books</a>
          </div>
        </div>
      <?php else: ?>
        <div class="cart-grid">
          <div class="cart-items-section">
            <form method="post" id="cartForm">
              <?php foreach ($items as $it): 
                $b = $it['book'];
                $cover = !empty($b['cover']) ? $b['cover'] : $fallbackCover;
                $coverUrl = resolve_cover_url($cover);
              ?>
                <div class="cart-item">
                  <div class="cart-item-image">
                    <img src="<?= htmlspecialchars($coverUrl) ?>" alt="<?= htmlspecialchars($b['title']) ?>">
                  </div>
                  <div class="cart-item-details">
                    <h3 class="cart-item-title"><?= htmlspecialchars($b['title']) ?></h3>
                    <p class="cart-item-author">by <?= htmlspecialchars($b['author']) ?></p>
                    <p class="cart-item-price"><?= format_price($b['price']) ?></p>
                  </div>
                  <div class="cart-item-quantity">
                    <label for="qty_<?= $b['id'] ?>">Qty:</label>
                    <select name="qty[<?= $b['id'] ?>]" id="qty_<?= $b['id'] ?>" class="cart-qty-select" onchange="document.getElementById('cartForm').submit()">
                      <?php for ($i = 0; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>"<?= $it['qty'] === $i ? ' selected' : '' ?>><?= $i === 0 ? 'Delete' : $i ?></option>
                      <?php endfor; ?>
                    </select>
                  </div>
                  <div class="cart-item-subtotal">
                    <?= format_price($b['price'] * $it['qty']) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </form>
          </div>

          <div class="cart-summary-section">
            <div class="cart-summary">
              <h2>Order Summary</h2>
              <div class="cart-summary-row">
                <span>Subtotal (<?= count($items) ?> <?= count($items) === 1 ? 'item' : 'items' ?>):</span>
                <span><?= format_price(cart_total()) ?></span>
              </div>
              <div class="cart-summary-total">
                <span>Total:</span>
                <span><?= format_price(cart_total()) ?></span>
              </div>
              <a href="checkout.php" class="btn cart-checkout-btn">Proceed to Checkout</a>
              <a href="books.php" class="btn cart-continue-link">Continue Shopping</a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
