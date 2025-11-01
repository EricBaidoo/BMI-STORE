<?php
require_once __DIR__ . '/includes/functions.php';
$all = all_books();
$search = trim($_GET['q'] ?? '');
$books = $all;
if ($search) {
  $books = array_filter($books, function($b) use ($search) {
    return stripos($b['title'] . ' ' . $b['author'] . ' ' . $b['description'], $search) !== false;
  });
}
// pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = max(6, (int)($_GET['per_page'] ?? 8));
$books = array_values($books);
$total = count($books);
$pages = max(1, ceil($total / $perPage));
if ($page > $pages) $page = $pages;
$start = ($page - 1) * $perPage;
$books = array_slice($books, $start, $perPage);
require_once __DIR__ . '/includes/header.php';
?>
<div class="books-landing container py-4">
  <div class="books-header mb-4">
  <h2 class="books-page-title mb-3">Welcome to Our Church Bookstore</h2>
    <div class="d-flex flex-column flex-md-row justify-content-end align-items-center gap-3">
      <form class="d-flex books-search-form" method="get" action="books.php">
      <input class="form-control me-2 books-search-input" type="search" name="q" placeholder="Search books..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-primary books-search-btn" type="submit">Search</button>
    </form>
  </div>
  <div class="product-grid row g-4 mt-1">
    <?php foreach ($books as $b): ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
        <div class="card book-card flex-fill h-100">
          <img loading="lazy" src="<?= htmlspecialchars($b['cover']) ?>" class="book-card-img card-img-top" alt="<?= htmlspecialchars($b['title']) ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title book-title mb-1"><?= htmlspecialchars($b['title']) ?></h5>
            <p class="card-text text-muted small mb-2 book-author"><?= htmlspecialchars($b['author']) ?></p>
            <p class="mt-auto mb-2"><strong><?= format_price($b['price']) ?></strong></p>
            <a href="book.php?id=<?= urlencode($b['id']) ?>" class="btn btn-outline-primary btn-sm w-100">View Details</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if (empty($books)): ?>
      <div class="col-12 text-center text-muted py-5">
        <em>No books found.</em>
      </div>
    <?php endif; ?>
  </div>
  <?php if ($pages > 1): ?>
    <nav aria-label="Books pagination" class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($p = 1; $p <= $pages; $p++): ?>
          <li class="page-item<?= $p===$page? ' active':'' ?>">
            <a class="page-link" href="books.php?page=<?= $p ?>&per_page=<?= $perPage ?><?= $search? '&q='.urlencode($search):'' ?>"><?= $p ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
