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
<div class="book-page">
  <div class="book-hero">
    <div class="container book-hero-inner">
      <nav class="book-breadcrumbs" aria-label="Breadcrumb">
        <a href="index.php">Home</a>
        <span>/</span>
        <a href="books.php">Books</a>
        <span>/</span>
        <span><?= htmlspecialchars($book['title']) ?></span>
      </nav>
      <h1><?= htmlspecialchars($book['title']) ?></h1>
      <p class="book-hero-sub">by <?= htmlspecialchars($book['author']) ?></p>
    </div>
  </div>

  <div class="container book-shell">
    <a href="books.php" class="book-back-link d-inline-flex align-items-center"><i class="bi bi-arrow-left me-2"></i>Back to catalog</a>

    <div class="book-grid">
      <div class="book-gallery">
        <div class="book-cover-frame">
          <img src="<?= htmlspecialchars($coverUrl) ?>" class="book-cover" alt="<?= htmlspecialchars($book['title']) ?>">
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
      </div>

      <div class="book-info">
        <div class="book-price">
          <span><?= format_price($book['price']) ?></span>
        </div>

        <div class="book-meta">
          <?php if (!empty($book['category'])): ?>
            <div><span>Category</span><strong><?= htmlspecialchars($book['category']) ?></strong></div>
          <?php endif; ?>
          <div><span>Stock</span><strong><?= $stockCount ?></strong></div>
          <div><span>Book ID</span><strong><?= htmlspecialchars((string)($book['id'] ?? '')) ?></strong></div>
        </div>

        <div class="book-description">
          <?= nl2br(htmlspecialchars($book['description'])) ?>
        </div>

        <form method="post" class="book-buy">
          <div class="book-qty">
            <label for="qty" class="book-qty-label">Quantity</label>
            <input id="qty" type="number" name="qty" value="1" min="1" class="form-control" aria-label="Quantity">
          </div>
          <button class="btn book-cta" type="submit"><i class="bi bi-cart-plus me-2"></i>Add to cart</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
