<?php
require_once __DIR__ . '/includes/functions.php';
$page_css = 'contact.css';

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

require_once __DIR__ . '/includes/header.php';
?>

<main class="contact-page">
  <!-- Hero Section -->
  <section class="contact-hero">
    <div class="container">
      <h1>Contact Us</h1>
      <p class="contact-hero-desc">Have questions or need assistance? We're here to help you find the perfect book.</p>
    </div>
  </section>

  <!-- Contact Grid -->
  <section class="contact-content">
    <div class="container">
      <div class="contact-grid">
        <!-- Contact Info -->
        <div class="contact-info-card">
          <h2>Get in Touch</h2>
          <div class="contact-info-items">
            <div class="contact-info-item">
              <div class="contact-info-icon">
                <i class="bi bi-geo-alt-fill"></i>
              </div>
              <div class="contact-info-text">
                <strong>Address</strong>
                <p>123 Church Street<br>Downtown District<br>Your City, Country</p>
              </div>
            </div>
            <div class="contact-info-item">
              <div class="contact-info-icon">
                <i class="bi bi-telephone-fill"></i>
              </div>
              <div class="contact-info-text">
                <strong>Phone</strong>
                <p>+1 (555) 123-4567</p>
              </div>
            </div>
            <div class="contact-info-item">
              <div class="contact-info-icon">
                <i class="bi bi-envelope-fill"></i>
              </div>
              <div class="contact-info-text">
                <strong>Email</strong>
                <p>info@churchbookstore.com</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-card">
          <h2>Send us a Message</h2>
          <form class="contact-form" method="post" action="contact.php" novalidate>
            <?php if ($success): ?>
              <div class="alert alert-success">Thank you for reaching out! We'll respond as soon as possible.</div>
            <?php elseif ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="form-group">
              <label for="name" class="form-label">Your Name</label>
              <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Full name">
            </div>
            <div class="form-group">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@example.com">
            </div>
            <div class="form-group">
              <label for="message" class="form-label">Your Message</label>
              <textarea class="form-control" id="message" name="message" rows="5" required placeholder="How can we help you?"><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="contact-submit-btn">Send Message</button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="contact-map">
    <div class="container">
      <h2>Find Us</h2>
      <div class="contact-map-embed">
        <iframe src="https://www.google.com/maps?q=church+near+me&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
