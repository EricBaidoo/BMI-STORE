<?php
// Shared header — assumes session started in functions.php
require_once __DIR__ . '/functions.php';
$cartCount = array_sum($_SESSION['cart'] ?? []);

// Determine per-page stylesheet name. Pages may set $page_css before including header.
$pageCss = '';
if (!empty($page_css)) {
    $pageCss = $page_css;
} else {
    $script = basename($_SERVER['SCRIPT_NAME']);
    $name = preg_replace('/\.php$/', '', $script);
    $pageCss = $name . '.css';
}

// Optional meta description
$metaDescription = $meta_description ?? 'Buy Christian books and resources at ' . (site_name());
// current script name for active link highlighting
$currentScript = basename($_SERVER['SCRIPT_NAME'] ?? '');
// asset base path (root of project) so admin pages resolve assets correctly
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
$assetPrefix = $scriptDir;
if ($assetPrefix === '' || $assetPrefix === '.') $assetPrefix = '';
if (basename($assetPrefix) === 'admin') {
  $assetPrefix = dirname($assetPrefix);
}
if ($assetPrefix === '\\' || $assetPrefix === '.') $assetPrefix = '';
?>
<!-- Skip link for keyboard users -->
<a class="skip-link visually-hidden-focusable" href="#main">Skip to main content</a>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars(site_name()) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
  <link href="<?= htmlspecialchars($assetPrefix) ?>/assets/css/styles.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= htmlspecialchars($assetPrefix) ?>/assets/css/header.css" rel="stylesheet">
  <?php
  // Include page CSS if present
  $possiblePath = __DIR__ . '/../assets/css/' . $pageCss;
  if (!empty($pageCss) && file_exists($possiblePath)) {
    $pageHref = htmlspecialchars($assetPrefix) . '/assets/css/' . htmlspecialchars($pageCss);
    echo "<link href=\"{$pageHref}\" rel=\"stylesheet\">\n";
  }
  ?>
</head>
<body>
<header class="site-header" style="margin-bottom:0;">
  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="<?= htmlspecialchars($assetPrefix) ?>/index.php">
        <div class="brand-mark me-2" aria-hidden="true">
          <!-- prefer assets/images/logo.svg, then assets/img/author.svg, else inline fallback -->
          <?php
          $logoPath = '';
          if (file_exists(__DIR__ . '/../assets/images/logo.svg')) {
            $logoPath = 'assets/images/logo.svg';
          } elseif (file_exists(__DIR__ . '/../assets/img/author.svg')) {
            $logoPath = 'assets/img/author.svg';
          }
          if ($logoPath): ?>
            <img src="<?= htmlspecialchars($assetPrefix) ?>/<?= htmlspecialchars($logoPath) ?>" alt="<?= htmlspecialchars(site_name()) ?> logo" class="brand-logo">
          <?php else: ?>
            <!-- fallback inline SVG -->
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <rect x="2" y="4" width="6" height="14" rx="1" fill="#fff" opacity="0.06" />
              <path d="M3 5h6v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5z" fill="#0d6efd" />
              <path d="M9 5h11v12a1 1 0 0 1-1 1H10a1 1 0 0 1-1-1V5z" fill="#6610f2" />
            </svg>
          <?php endif; ?>
        </div>
        <div>
          <div class="brand-name"><?= htmlspecialchars(site_name()) ?></div>
    
        </div>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNav" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-center" id="siteNav">
        <?php
        // Build categories from books data for the dropdown
        $booksList = all_books();
        $cats = [];
        if (is_array($booksList)) {
          foreach ($booksList as $b) {
            if (!empty($b['category'])) $cats[$b['category']] = true;
          }
        }
        $categories = array_keys($cats);
        sort($categories);
        $topCategories = array_slice($categories, 0, 8);
        ?>

  <ul class="navbar-nav align-items-lg-center navbar-nav-centered">
          <li class="nav-item me-3"><a class="nav-link <?= $currentScript === 'index.php' ? 'active' : '' ?>" href="<?= htmlspecialchars($assetPrefix) ?>/index.php">Home</a></li>
          <li class="nav-item me-3"><a class="nav-link <?= $currentScript === 'books.php' ? 'active' : '' ?>" href="<?= htmlspecialchars($assetPrefix) ?>/books.php">Books</a></li>
          <li class="nav-item me-3"><a class="nav-link <?= $currentScript === 'contact.php' ? 'active' : '' ?>" href="<?= htmlspecialchars($assetPrefix) ?>/contact.php">Contact</a></li>
          <li class="nav-item me-3">
            <a class="nav-link" href="https://bridgeministriesintl.org" target="_blank" rel="noopener noreferrer">Bridge Ministries</a>
          </li>
        </ul>
      </div>

    

        <a class="btn cart-btn position-relative" href="<?= htmlspecialchars($assetPrefix) ?>/cart.php" aria-label="Cart">
          <i class="bi bi-cart" aria-hidden="true"></i>
          <span class="ms-1">Cart</span>
          <span class="cart-badge rounded-pill position-absolute top-0 start-100 translate-middle"><?= $cartCount ?: 0 ?></span>
        </a>
      </div>

      <!-- For small screens: show search inside collapse so it's accessible when menu opens -->
      <div class="d-lg-none mt-2 w-100">
        <form class="d-flex" role="search" action="<?= htmlspecialchars($assetPrefix) ?>/books.php" method="get">
          <input class="form-control me-2 header-search" type="search" placeholder="Search books, authors..." aria-label="Search" name="q">
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
</header>