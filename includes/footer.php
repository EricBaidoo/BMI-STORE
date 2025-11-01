</div>
</div>

<footer class="site-footer py-5" role="contentinfo">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-4 col-md-6">
        <div class="d-flex align-items-center mb-2">
          <?php if (file_exists(__DIR__ . '/../assets/images/logo.svg')): ?>
            <img src="assets/images/logo.svg" alt="<?= htmlspecialchars(site_name()) ?>" class="footer-logo me-2">
          <?php elseif (file_exists(__DIR__ . '/../assets/img/author.svg')): ?>
            <img src="assets/img/author.svg" alt="<?= htmlspecialchars(site_name()) ?>" class="footer-logo me-2">
          <?php endif; ?>
          <div>
            <div class="h6 mb-0 text-white"><?= htmlspecialchars(site_name()) ?></div>
            <div class="small text-muted-light">Books for faith & life</div>
          </div>
        </div>
        <p class="text-muted-light small mb-0">Curated books to help you grow in faith, leadership and everyday life. Fast shipping and friendly support.</p>
      </div>

      <nav class="col-lg-2 col-md-6" aria-label="Shop links">
        <h6 class="text-white">Shop</h6>
        <ul class="list-unstyled mb-0">
          <li><a class="footer-link" href="books.php">Books</a></li>
          <li><a class="footer-link" href="index.php">Home</a></li>
          <li><a class="footer-link" href="contact.php">Contact</a></li>
        </ul>
      </nav>

      <nav class="col-lg-2 col-md-6" aria-label="Support links">
        <h6 class="text-white">Support</h6>
        <ul class="list-unstyled mb-0">
          <li><a class="footer-link" href="privacy.php">Privacy</a></li>
          <li><a class="footer-link" href="terms.php">Terms</a></li>
          <li><a class="footer-link" href="faq.php">FAQ</a></li>
        </ul>
      </nav>

      <div class="col-lg-4 col-md-6">
        <h6 class="text-white">Newsletter</h6>
        <form action="subscribe.php" method="post" class="d-flex footer-subscribe align-items-start" novalidate>
          <input type="email" name="email" class="form-control me-2" placeholder="Your email" aria-label="Email address" required>
          <button class="btn btn-accent mt-0" type="submit">Join</button>
        </form>
        <div class="mt-3 d-flex align-items-center gap-2">
          <a class="social-link" href="#" aria-label="facebook"><i class="bi bi-facebook"></i></a>
          <a class="social-link" href="#" aria-label="twitter"><i class="bi bi-twitter"></i></a>
          <a class="social-link" href="#" aria-label="instagram"><i class="bi bi-instagram"></i></a>
          <div class="ms-auto d-none d-md-inline-block payment-icons" aria-hidden="true">
            <!-- placeholder for payment icons if you add them later -->
            <span class="small text-muted-light">Visa • MasterCard • PayPal</span>
          </div>
        </div>
      </div>
    </div>

  <!-- separator between footer content and copyright row -->
  <div class="footer-sep" aria-hidden="true"></div>

  <div class="row mt-4">
      <div class="col-12 d-flex justify-content-center align-items-center gap-3">
        <div class="small text-muted-light">&copy; <?= date('Y') ?> <?= htmlspecialchars(site_name()) ?></div>
        <div class="small text-muted-light">Powered by Bridge Ministries</div>
      </div>
    </div>
  </div>
</footer>

<link href="assets/css/footer.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/scripts.js"></script>
</body>
</html>
