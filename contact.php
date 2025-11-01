
<?php
require_once __DIR__ . '/includes/header.php';
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
        $success = true;
    } else {
        $error = 'Please fill in all fields with a valid email.';
    }
}
?>

<section class="contact-hero">
  <div class="contact-hero-content">
    <h1 class="contact-hero-title">Get in Touch with Our Church Bookstore</h1>
    <p class="contact-hero-desc">We’re here to help you find the right resources and answer your questions. Reach out to us below!</p>
  </div>
</section>

<main class="contact-main">
  <div class="contact-main-inner">
    <div class="contact-main-grid">
      <div class="contact-main-left contact-card">
        <img src="assets/images/41fSSwll0vL._SY445_SX342_.jpg" alt="Book cover" class="contact-main-book">
        <div class="contact-info">
          <h3 class="contact-info-heading">Contact Information</h3>
          <div class="contact-info-row icon-only">
            <span class="contact-info-icon"><i class="bi bi-geo-alt-fill"></i></span>
            <span class="contact-info-detail">
              123 Church Street<br>
              Downtown District<br>
              Your City, Country
            </span>
          </div>
          <div class="contact-info-divider"></div>
          <div class="contact-info-row icon-only">
            <span class="contact-info-icon"><i class="bi bi-telephone-fill"></i></span>
            <span class="contact-info-detail">+1 (555) 123-4567</span>
          </div>
          <div class="contact-info-divider"></div>
          <div class="contact-info-row icon-only">
            <span class="contact-info-icon"><i class="bi bi-envelope-fill"></i></span>
            <span class="contact-info-detail">info@churchbookstore.com</span>
          </div>
        </div>
      </div>
      <div class="contact-main-right">
        <form class="contact-form" method="post" action="contact.php" novalidate>
          <?php if ($success): ?>
            <div class="alert alert-success text-center">Thank you for reaching out! We'll respond as soon as possible.</div>
          <?php elseif ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Your full name">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@email.com">
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required placeholder="How can we help you?"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100 contact-submit-btn">Send Message</button>
        </form>
      </div>
    </div>
  </div>
</main>

<section class="contact-map-section">
  <div class="contact-map-box">
    <h3 class="contact-map-heading"><i class="bi bi-geo-alt-fill"></i> Find Us</h3>
    <div class="contact-map-embed">
      <iframe src="https://www.google.com/maps?q=church+near+me&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
