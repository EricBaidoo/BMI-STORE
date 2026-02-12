<?php
require_once __DIR__ . '/../includes/functions.php';
// Protect admin pages
require_once __DIR__ . '/_auth.php';

$error = '';
$values = [
  'title' => '',
  'author' => '',
  'price' => '',
  'stock' => '',
  'cover' => '',
  'category' => '',
  'description' => '',
  'featured' => false,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $values['title'] = trim($_POST['title'] ?? '');
  $values['author'] = trim($_POST['author'] ?? '');
  $values['price'] = $_POST['price'] ?? '';
  $values['stock'] = $_POST['stock'] ?? '';
  $values['cover'] = trim($_POST['cover'] ?? '');
  $values['category'] = trim($_POST['category'] ?? '');
  $values['description'] = trim($_POST['description'] ?? '');
  $values['featured'] = isset($_POST['featured']);

  $title = $values['title'];
  $author = $values['author'];
  $price = is_numeric($values['price']) ? (float)$values['price'] : 0.0;
  $stock = is_numeric($values['stock']) ? (int)$values['stock'] : 0;
  $coverUrl = $values['cover'];
  $category = $values['category'];
  $description = $values['description'];
  $featured = $values['featured'] ? 1 : 0;

  $slugBase = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
  $slug = trim($slugBase, '-');

  if ($title === '') {
    $error = 'Title is required.';
  }

  $uploadedCover = '';
  if (!$error && !empty($_FILES['cover_file']) && $_FILES['cover_file']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['cover_file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
      $error = 'Cover upload failed. Please try again.';
    } elseif ($file['size'] > 2 * 1024 * 1024) {
      $error = 'Cover image must be 2MB or smaller.';
    } else {
      $imageInfo = @getimagesize($file['tmp_name']);
      $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
      ];
      if (!$imageInfo || empty($allowedMimes[$imageInfo['mime'] ?? ''])) {
        $error = 'Cover file must be a valid image (jpg, png, webp, gif).';
      } else {
        $uploadsDir = __DIR__ . '/../assets/images/uploads';
        if (!is_dir($uploadsDir)) {
          mkdir($uploadsDir, 0775, true);
        }
        $ext = $allowedMimes[$imageInfo['mime']];
        $safeSlug = $slug !== '' ? $slug : 'book';
        $filename = time() . '_' . $safeSlug . '.' . $ext;
        $targetPath = $uploadsDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
          $error = 'Cover upload could not be saved.';
        } else {
          $uploadedCover = 'assets/images/uploads/' . $filename;
        }
      }
    }
  }

  $cover = $uploadedCover ?: $coverUrl;

  if (!$error) {
    $pdo = get_pdo();
    if ($pdo) {
      try {
        $stmt = $pdo->prepare('INSERT INTO books (slug, title, author, price, stock, cover, category, description, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$slug, $title, $author, $price, $stock, $cover, $category, $description, $featured]);
        header('Location: dashboard.php');
        exit;
      } catch (Exception $e) {
        $error = 'Could not save the book to the database. Check your DB config and table schema.';
      }
    } else {
      $error = 'Database connection is unavailable. Please check your DB settings.';
    }
  }
}
require_once __DIR__ . '/../includes/header.php';
?>
  <div class="admin-book-page">
    <div class="admin-book-card">
      <div class="admin-book-header">
        <div>
          <h1 class="admin-book-title">Add a New Book</h1>
          <p class="admin-book-subtitle">Fill in the details below to publish a new title to your store.</p>
        </div>
        <a class="btn btn-outline-secondary" href="dashboard.php">Back to dashboard</a>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data" class="admin-book-form">
        <div class="admin-grid">
          <div class="admin-field">
            <label class="form-label" for="book-title">Title</label>
            <input id="book-title" name="title" class="form-control" required value="<?= htmlspecialchars($values['title']) ?>">
          </div>
          <div class="admin-field">
            <label class="form-label" for="book-author">Author</label>
            <input id="book-author" name="author" class="form-control" value="<?= htmlspecialchars($values['author']) ?>">
          </div>
          <div class="admin-field">
            <label class="form-label" for="book-price">Price</label>
            <input id="book-price" name="price" class="form-control" type="number" step="0.01" min="0" value="<?= htmlspecialchars($values['price']) ?>">
          </div>
          <div class="admin-field">
            <label class="form-label" for="book-stock">Stock</label>
            <input id="book-stock" name="stock" class="form-control" type="number" min="0" value="<?= htmlspecialchars($values['stock']) ?>">
          </div>
          <div class="admin-field">
            <label class="form-label" for="book-category">Category</label>
            <input id="book-category" name="category" class="form-control" value="<?= htmlspecialchars($values['category']) ?>">
          </div>
          <div class="admin-field">
            <label class="form-label" for="book-cover">Cover URL</label>
            <input id="book-cover" name="cover" class="form-control" placeholder="https://" value="<?= htmlspecialchars($values['cover']) ?>">
          </div>
          <div class="admin-field">
            <label class="form-label" for="book-cover-file">Cover Upload</label>
            <input id="book-cover-file" name="cover_file" class="form-control" type="file" accept="image/*">
            <small class="admin-help">Upload an image or leave blank to use the cover URL.</small>
          </div>
          <div class="admin-field admin-field-featured">
            <label class="form-label">Featured</label>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="book-featured" name="featured"<?= $values['featured'] ? ' checked' : '' ?>>
              <label class="form-check-label" for="book-featured">Show on homepage highlights</label>
            </div>
          </div>
          <div class="admin-field admin-field-full">
            <label class="form-label" for="book-description">Description</label>
            <textarea id="book-description" name="description" class="form-control" rows="5"><?= htmlspecialchars($values['description']) ?></textarea>
          </div>
        </div>

        <div class="admin-actions">
          <button class="btn btn-primary">Add book</button>
          <a class="btn btn-outline-secondary" href="dashboard.php">Cancel</a>
        </div>
      </form>
    </div>
  </div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
