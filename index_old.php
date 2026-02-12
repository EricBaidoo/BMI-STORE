<?php
require_once __DIR__ . '/includes/functions.php';
$page_css = 'index.css';

$featuredBooks = featured_books(6);
if (empty($featuredBooks)) {
  $featuredBooks = array_slice(all_books(), 0, 6);
}
$fallbackCover = 'assets/images/books%20main.png';

require_once __DIR__ . '/includes/header.php';
?>

<main class="home-page">
  <!-- Hero Section -->
  <section class="home-hero">
    <div class="container">
      <div class="home-hero-grid">
        <div class="home-hero-content">
          <p class="home-kicker">Welcome to BMI Store</p>
          <h1>Books that inspire faith, ignite hope, and transform lives</h1>
          <p class="home-subtitle">Discover powerful titles from trusted authors—curated for every believer seeking truth, growth, and purpose.</p>
          <div class="home-hero-actions">
            <a href="books.php" class="btn home-cta-primary">Browse Catalog</a>
            <a href="#featured" class="btn home-cta-secondary">Featured Books</a>
          </div>
        </div>
        <div class="home-hero-visual">
          <img src="assets/images/477983923_1258677485680214_2499823974197020767_n.jpg" alt="Book collection" loading="lazy">
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Books Section -->
  <section class="home-featured" id="featured">
    <div class="container">
      <div class="home-section-header">
        <h2>Featured Books</h2>
        <a href="books.php" class="home-section-link">View all</a>
      </div>
      <div class="home-books-grid">
        <?php foreach ($featuredBooks as $book): ?>
          <?php
            $cover = !empty($book['cover']) ? $book['cover'] : $fallbackCover;
            $coverUrl = resolve_cover_url($cover);
            $bookUrl = 'book.php?id=' . urlencode($book['id']);
          ?>
          <article class="home-book-card">
            <a class="home-book-media" href="<?= htmlspecialchars($bookUrl) ?>">
              <img loading="lazy" src="<?= htmlspecialchars($coverUrl) ?>" alt="Cover of <?= htmlspecialchars($book['title'] ?? 'Book') ?>">
            </a>
            <div class="home-book-body">
              <h3 class="home-book-title">
                <a href="<?= htmlspecialchars($bookUrl) ?>"><?= htmlspecialchars($book['title']) ?></a>
              </h3>
              <p class="home-book-author"><?= htmlspecialchars($book['author']) ?></p>
              <div class="home-book-footer">
                <span class="home-book-price"><?= format_price($book['price']) ?></span>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Author Spotlight Section -->
  <section class="home-author">
    <div class="container">
      <div class="home-author-grid">
        <div class="home-author-photo">
          <img src="assets/images/author photo.JPG" alt="Rev. F. D. Yalley" loading="lazy">
        </div>
        <div class="home-author-content">
          <p class="home-kicker">Author Spotlight</p>
          <h2>Rev. Francis Duane Yalley</h2>
          <p>F. D. Yalley, Founder and Senior Pastor of Bridge Ministries International, is affectionately called the REPAIRER. His dynamic, lightning rod teaching style inspires a deep love and desire to serve God.</p>
          <p>Pastor Yalley's messages are direct and rooted in scripture, motivating listeners to strengthen their faith and pursue a purposeful spiritual life.</p>
          <p>He makes serving God accessible and joyful, fostering a community where devotion and service are at the heart of the ministry.</p>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
