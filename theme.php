<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>
  <h1>Theme preview</h1>
  <p class="text-muted">This page shows the main UI elements based on the central stylesheet.</p>

  <section class="mb-4">
    <h2>Buttons</h2>
    <button class="btn btn-primary">Primary</button>
    <button class="btn btn-outline-primary">Outline</button>
    <button class="btn btn-secondary">Secondary</button>
  </section>

  <section class="mb-4">
    <h2>Cards</h2>
    <div class="product-grid">
      <div class="card">
        <img class="product-card-img" src="https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?w=600&h=900&fit=crop" alt="cover">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="text-muted small">Author name</p>
          <p class="mt-2"><strong>$12.99</strong></p>
        </div>
      </div>
      <div class="card">
        <img class="product-card-img" src="https://images.unsplash.com/photo-1526045612212-70caf35c14df?w=600&h=900&fit=crop" alt="cover">
        <div class="card-body">
          <h5 class="card-title">Card title</h5>
          <p class="text-muted small">Author name</p>
          <p class="mt-2"><strong>$9.99</strong></p>
        </div>
      </div>
    </div>
  </section>

  <section class="mb-4">
    <h2>Form</h2>
    <form>
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input class="form-control" placeholder="Your name">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" placeholder="you@example.com">
      </div>
      <button class="btn btn-primary">Submit</button>
    </form>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
