<?php
require_once __DIR__ . '/includes/functions.php';
$id = $_GET['id'] ?? null;
$book = $id ? find_book($id) : null;
if (!$book) {
    header('HTTP/1.0 404 Not Found');
    echo 'Book not found';
    exit;
}
$coverUrl = resolve_cover_url($book['cover'] ?? '');
$coverUrl = $coverUrl ?: 'assets/images/books%20main.png';
$stockCount = (int)($book['stock'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    cart_add($book['id'], $qty);
    header('Location: cart.php');
    exit;
}
require_once __DIR__ . '/includes/header.php';
?>
<main id="main" class="book-page">
  <div class="book-hero">
    <div class="container book-hero-inner">
      <nav class="book-breadcrumbs" aria-label="Breadcrumb">
        <a href="index.php">Home</a>
        <span>/</span>
        <a href="books.php">Books</a>
        <span>/</span>
        <span><?= htmlspecialchars($book['title']) ?></span>
      </nav>
      <div class="book-hero-title">
        <h1><?= htmlspecialchars($book['title']) ?></h1>
        <p class="book-hero-sub">by <?= htmlspecialchars($book['author']) ?></p>
      </div>
    </div>
  </div>

  <div class="container book-shell">
    <a href="books.php" class="book-back-link d-inline-flex align-items-center"><i class="bi bi-arrow-left me-2"></i>Back to catalog</a>

    <div class="book-grid">
      <div class="book-gallery">
        <div class="book-cover-frame">
          <img src="<?= htmlspecialchars($coverUrl) ?>" class="book-cover" alt="<?= htmlspecialchars($book['title']) ?>">
        </div>
        <div class="book-thumb-list" aria-hidden="true">
          <button class="book-thumb is-active" type="button">
            <img src="<?= htmlspecialchars($coverUrl) ?>" alt="">
          </button>
        </div>
      </div>

      <div class="book-info">
        <div class="book-title-block">
          <h2 class="book-title"><?= htmlspecialchars($book['title']) ?></h2>
          <p class="book-author">by <?= htmlspecialchars($book['author']) ?></p>
          <div class="book-rating" aria-label="No ratings yet">
            <span class="stars">★★★★★</span>
            <span class="book-rating-text">No ratings yet</span>
          </div>
        </div>

        <div class="book-price-row">
          <span class="book-price-label">Price</span>
          <span class="book-price-value"><?= format_price($book['price']) ?></span>
        </div>

        <div class="book-about">
          <h3>About this book</h3>
          <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
        </div>

        <div class="book-details">
          <h3>Details</h3>
          <dl>
            <?php if (!empty($book['category'])): ?>
              <div>
                <dt>Category</dt>
                <dd><?= htmlspecialchars($book['category']) ?></dd>
              </div>
            <?php endif; ?>
            <div>
              <dt>Stock</dt>
              <dd><?= $stockCount ?></dd>
            </div>
            <div>
              <dt>Book ID</dt>
              <dd><?= htmlspecialchars((string)($book['id'] ?? '')) ?></dd>
            </div>
          </dl>
        </div>
      </div>

      <aside class="book-aside">
        <div class="book-buy-card">
          <div class="book-buy-price"><?= format_price($book['price']) ?></div>
          <div class="book-stock-line <?= $stockCount > 0 ? 'in-stock' : 'out-stock' ?>">
            <?= $stockCount > 0 ? 'In Stock' : 'Currently unavailable' ?>
          </div>
          <div class="book-delivery">
            <div><i class="bi bi-truck"></i><span>Delivery in 2-5 business days</span></div>
            <div><i class="bi bi-arrow-repeat"></i><span>Free returns within 7 days</span></div>
          </div>
          <form method="post" class="book-buy">
            <div class="book-qty">
              <label for="qty" class="book-qty-label">Quantity</label>
              <input id="qty" type="number" name="qty" value="1" min="1" class="form-control" aria-label="Quantity">
            </div>
            <button class="btn book-cta" type="submit"><i class="bi bi-cart-plus me-2"></i>Add to cart</button>
          </form>
          <div class="book-safe">
            <i class="bi bi-shield-check"></i>
            <span>Secure transaction</span>
          </div>
        </div>
        <div class="book-badges">
          <?php if (!empty($book['featured'])): ?>
            <span class="book-badge">Featured</span>
          <?php endif; ?>
          <?php if ($stockCount > 0): ?>
            <span class="book-badge book-badge-light">In stock</span>
          <?php else: ?>
            <span class="book-badge book-badge-outline">Out of stock</span>
          <?php endif; ?>
        </div>
      </aside>
    </div>
  </div>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
