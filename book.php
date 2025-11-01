<?php
require_once __DIR__ . '/includes/functions.php';
$id = $_GET['id'] ?? null;
$book = $id ? find_book($id) : null;
if (!$book) {
    header('HTTP/1.0 404 Not Found');
    echo 'Book not found';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    cart_add($book['id'], $qty);
    header('Location: cart.php');
    exit;
}
require_once __DIR__ . '/includes/header.php';
?>
<div class="book-modern-bg py-5">
  <div class="book-modern-container container">
    <a href="books.php" class="book-back-link mb-4 d-inline-flex align-items-center"><i class="bi bi-arrow-left me-2"></i>Back to catalog</a>
    <div class="book-modern-card row g-0 shadow-lg">
      <div class="col-12 col-md-5 book-modern-sidebar d-flex flex-column align-items-center justify-content-center p-4">
        <div class="book-modern-img-wrap position-relative mb-3">
          <img src="<?= htmlspecialchars($book['cover']) ?>" class="book-modern-img img-fluid" alt="<?= htmlspecialchars($book['title']) ?>">

        </div>
        <div class="book-modern-author text-center mt-2">
          <span class="text-muted">by</span> <span class="fw-bold"><?= htmlspecialchars($book['author']) ?></span>
        </div>
      </div>
      <div class="col-12 col-md-7 book-modern-info d-flex flex-column justify-content-center p-5">
        <h1 class="book-modern-title mb-3 text-break"><?= htmlspecialchars($book['title']) ?></h1>
        <div class="book-modern-price mb-3"><span><?= format_price($book['price']) ?></span></div>
        <div class="book-modern-desc mb-4 flex-grow-1"><?= nl2br(htmlspecialchars($book['description'])) ?></div>
        <form method="post" class="book-modern-form d-flex align-items-center gap-3 mt-auto">
          <input type="number" name="qty" value="1" min="1" class="form-control book-modern-qty" aria-label="Quantity">
          <button class="btn btn-gradient book-modern-addcart d-flex align-items-center" type="submit"><i class="bi bi-cart-plus me-2"></i>Add to cart</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
