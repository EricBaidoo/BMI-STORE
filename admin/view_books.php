<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_auth.php';
$page_css = 'view_books.css';

$books = all_books();
$assetPrefix = asset_prefix();

require_once __DIR__ . '/../includes/header.php';
?>
  <section class="admin-books-page">
    <div class="admin-books-header">
      <div>
        <p class="admin-kicker">Catalog</p>
        <h1>All Books</h1>
        <p class="admin-subtitle">Manage your full book inventory in one place.</p>
      </div>
      <div class="admin-books-actions">
        <a class="btn btn-primary" href="add_book.php"><i class="bi bi-plus-lg"></i> Add book</a>
        <a class="btn btn-outline-secondary" href="dashboard.php">Back to dashboard</a>
      </div>
    </div>

    <?php if (empty($books)): ?>
      <div class="admin-empty">No books found in the database.</div>
    <?php else: ?>
      <div class="admin-table-wrap">
        <table class="table admin-table align-middle">
          <thead>
            <tr>
              <th>Book</th>
              <th>Author</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Featured</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($books as $b): ?>
              <tr>
                <td>
                  <div class="admin-book-cell">
                    <?php if (!empty($b['cover'])): ?>
                      <?php $coverUrl = resolve_cover_url($b['cover']); ?>
                      <img src="<?= htmlspecialchars($coverUrl) ?>" alt="<?= htmlspecialchars($b['title'] ?? 'Book') ?>" class="admin-book-thumb">
                    <?php else: ?>
                      <div class="admin-book-thumb admin-book-thumb-fallback"><i class="bi bi-book"></i></div>
                    <?php endif; ?>
                    <div>
                      <div class="admin-book-title"><?= htmlspecialchars($b['title'] ?? '') ?></div>
                      <div class="admin-book-meta">ID: <?= htmlspecialchars((string)($b['id'] ?? '')) ?></div>
                    </div>
                  </div>
                </td>
                <td><?= htmlspecialchars($b['author'] ?? '-') ?></td>
                <td><?= format_price($b['price'] ?? 0) ?></td>
                <td><?= (int)($b['stock'] ?? 0) ?></td>
                <td><?= !empty($b['featured']) ? 'Yes' : 'No' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
