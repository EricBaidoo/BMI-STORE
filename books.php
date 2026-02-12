<?php
require_once __DIR__ . '/includes/functions.php';
$all = all_books();
$searchRaw = trim($_GET['q'] ?? '');
$search = $searchRaw;
if (function_exists('mb_substr')) {
  $search = mb_substr($searchRaw, 0, 100);
} else {
  $search = substr($searchRaw, 0, 100);
}
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$minPrice = is_numeric($minPrice) ? max(0, (float)$minPrice) : '';
$maxPrice = is_numeric($maxPrice) ? max(0, (float)$maxPrice) : '';
$priceActive = ($minPrice !== '' || $maxPrice !== '');
$sort = trim($_GET['sort'] ?? '');

$books = $all;

// search filter
if ($search !== '') {
  $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);
  if (!empty($terms)) {
    $books = array_filter($books, function($b) use ($terms) {
      $haystack = ($b['title'] ?? '') . ' ' . ($b['author'] ?? '') . ' ' . ($b['description'] ?? '') . ' ' . ($b['category'] ?? '');
      foreach ($terms as $term) {
        if (function_exists('mb_stripos')) {
          if (mb_stripos($haystack, $term) === false) return false;
        } else {
          if (stripos($haystack, $term) === false) return false;
        }
      }
      return true;
    });
  }
}

// price range filter
if ($priceActive) {
  $books = array_filter($books, function($b) use ($minPrice, $maxPrice) {
    $price = (float)($b['price'] ?? 0);
    if ($minPrice !== '' && $price < $minPrice) return false;
    if ($maxPrice !== '' && $price > $maxPrice) return false;
    return true;
  });
}

$books = array_values($books);

// sorting
switch ($sort) {
  case 'title_asc':
    usort($books, function($a, $b) {
      return strcasecmp($a['title'] ?? '', $b['title'] ?? '');
    });
    break;
  case 'price_asc':
    usort($books, function($a, $b) {
      return ($a['price'] ?? 0) <=> ($b['price'] ?? 0);
    });
    break;
  case 'price_desc':
    usort($books, function($a, $b) {
      return ($b['price'] ?? 0) <=> ($a['price'] ?? 0);
    });
    break;
  case 'newest':
    usort($books, function($a, $b) {
      return ($b['id'] ?? 0) <=> ($a['id'] ?? 0);
    });
    break;
  default:
    break;
}

// pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = (int)($_GET['per_page'] ?? 8);
if ($perPage < 6) $perPage = 8;
if ($perPage > 48) $perPage = 48;
$total = count($books);
$pages = max(1, (int)ceil($total / $perPage));
if ($page > $pages) $page = $pages;
$start = ($page - 1) * $perPage;
$books = array_slice($books, $start, $perPage);
$showingStart = $total > 0 ? $start + 1 : 0;
$showingEnd = $total > 0 ? min($start + count($books), $total) : 0;

$fallbackCover = 'assets/images/books%20main.png';

$hasFilters = ($search !== '' || $priceActive || $sort !== '' || $perPage !== 8);

$filterChips = [];
if ($hasFilters) {
  $filterChips[] = ['label' => 'Clear all', 'url' => 'books.php', 'class' => 'books-filter-chip-clear'];
}
if ($search !== '') {
  $qs = http_build_query(['min_price' => $minPrice, 'max_price' => $maxPrice, 'sort' => $sort, 'per_page' => $perPage]);
  $filterChips[] = ['label' => 'Search: ' . $search, 'url' => 'books.php' . ($qs ? '?' . $qs : '')];
}
if ($priceActive) {
  $rangeLabel = 'Price: ';
  if ($minPrice !== '' && $maxPrice !== '') {
    $rangeLabel .= format_price($minPrice) . '–' . format_price($maxPrice);
  } elseif ($minPrice !== '') {
    $rangeLabel .= 'from ' . format_price($minPrice);
  } else {
    $rangeLabel .= 'up to ' . format_price($maxPrice);
  }
  $qs = http_build_query(['q' => $search, 'sort' => $sort, 'per_page' => $perPage]);
  $filterChips[] = ['label' => $rangeLabel, 'url' => 'books.php' . ($qs ? '?' . $qs : '')];
}
if ($sort !== '') {
  $qs = http_build_query(['q' => $search, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'per_page' => $perPage]);
  $filterChips[] = ['label' => 'Sort: ' . $sort, 'url' => 'books.php' . ($qs ? '?' . $qs : '')];
}
if ($perPage !== 8) {
  $qs = http_build_query(['q' => $search, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'sort' => $sort]);
  $filterChips[] = ['label' => 'Per page: ' . $perPage, 'url' => 'books.php' . ($qs ? '?' . $qs : '')];
}
require_once __DIR__ . '/includes/header.php';
?>
<div class="books-landing container py-4">
  <div class="books-header mb-4">
    <div class="books-header-top">
      <div>
        <h2 class="books-page-title">Welcome to Our Church Bookstore</h2>
        <p class="books-subtitle">Discover faith-building reads, timeless classics, and new arrivals.</p>
      </div>
      <div class="books-results-count">
        <?php if ($total > 0): ?>
          Showing <?= $showingStart ?>–<?= $showingEnd ?> of <?= $total ?> results
        <?php else: ?>
          0 results
        <?php endif; ?>
      </div>
    </div>
    <div class="books-filter-panel">
      <form class="books-filter-form js-books-filters" method="get" action="books.php">
        <div class="books-filter-row">
          <div class="books-field books-field-search">
            <label for="books-search" class="books-label">Search</label>
            <input id="books-search" class="form-control books-input" type="search" name="q" placeholder="Search by title, author..." value="<?= htmlspecialchars($search) ?>">
          </div>
          <div class="books-field books-field-price">
            <label class="books-label" for="books-min-price">Price range</label>
            <div class="books-price-group">
              <input id="books-min-price" class="form-control books-price-input" type="number" name="min_price" step="0.01" min="0" placeholder="Min" value="<?= htmlspecialchars((string)$minPrice) ?>">
              <span class="books-price-sep" aria-hidden="true">–</span>
              <input id="books-max-price" class="form-control books-price-input" type="number" name="max_price" step="0.01" min="0" placeholder="Max" value="<?= htmlspecialchars((string)$maxPrice) ?>">
            </div>
          </div>
          <div class="books-field books-field-sort">
            <label for="books-sort" class="books-label">Sort</label>
            <select id="books-sort" class="form-select books-select" name="sort" aria-label="Sort books">
              <option value=""<?= $sort === '' ? ' selected' : '' ?>>Default</option>
              <option value="title_asc"<?= $sort === 'title_asc' ? ' selected' : '' ?>>Title A–Z</option>
              <option value="price_asc"<?= $sort === 'price_asc' ? ' selected' : '' ?>>Price: Low to High</option>
              <option value="price_desc"<?= $sort === 'price_desc' ? ' selected' : '' ?>>Price: High to Low</option>
              <option value="newest"<?= $sort === 'newest' ? ' selected' : '' ?>>Newest</option>
            </select>
          </div>
          <div class="books-field books-field-perpage">
            <label for="books-per-page" class="books-label">Per page</label>
            <select id="books-per-page" class="form-select books-select" name="per_page" aria-label="Results per page">
              <?php foreach ([6, 8, 12, 24, 48] as $size): ?>
                <option value="<?= $size ?>"<?= $perPage === $size ? ' selected' : '' ?>><?= $size ?> / page</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="books-field books-field-actions">
            <label class="books-label" aria-hidden="true">&nbsp;</label>
            <div class="books-action-group">
              <button class="btn btn-primary books-action-btn" type="submit">Apply</button>
              <?php if ($search !== '' || $priceActive || $sort !== ''): ?>
                <a class="btn btn-outline-secondary books-clear-btn" href="books.php">Clear</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php if (!empty($filterChips)): ?>
      <div class="books-filters">
        <?php foreach ($filterChips as $chip): ?>
          <a class="books-filter-chip<?= !empty($chip['class']) ? ' ' . htmlspecialchars($chip['class']) : '' ?>" href="<?= htmlspecialchars($chip['url']) ?>" aria-label="Remove filter: <?= htmlspecialchars($chip['label']) ?>">
            <?= htmlspecialchars($chip['label']) ?> <span aria-hidden="true">&times;</span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="product-grid row g-4 mt-1">
    <?php foreach ($books as $b): ?>
      <?php $cover = !empty($b['cover']) ? $b['cover'] : $fallbackCover; ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
        <div class="card book-card flex-fill h-100">
          <img loading="lazy" src="<?= htmlspecialchars($cover) ?>" class="book-card-img card-img-top" alt="Cover of <?= htmlspecialchars($b['title'] ?? 'Book') ?> by <?= htmlspecialchars($b['author'] ?? 'Unknown') ?>">
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
      <div class="col-12 py-5">
        <div class="books-empty">
          <h4>No books found</h4>
          <p>Try adjusting your search or filters, or browse all books.</p>
          <div class="books-empty-actions">
            <a class="btn btn-outline-primary" href="books.php">Browse all books</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <?php if ($pages > 1): ?>
    <?php
      $baseParams = [];
      if ($search !== '') $baseParams['q'] = $search;
      if ($minPrice !== '') $baseParams['min_price'] = $minPrice;
      if ($maxPrice !== '') $baseParams['max_price'] = $maxPrice;
      if ($sort !== '') $baseParams['sort'] = $sort;
      if ($perPage !== 8) $baseParams['per_page'] = $perPage;
    ?>
    <nav aria-label="Books pagination" class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($p = 1; $p <= $pages; $p++): ?>
          <li class="page-item<?= $p===$page? ' active':'' ?>">
            <?php $qs = http_build_query(array_merge($baseParams, ['page' => $p])); ?>
            <a class="page-link" href="books.php<?= $qs ? '?' . $qs : '' ?>"><?= $p ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
