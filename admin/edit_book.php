<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/_auth.php';

$page_css = 'add_book.css';

$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$book_id) {
  header('Location: view_books.php');
  exit;
}

$book = find_book($book_id);
if (!$book) {
  header('Location: view_books.php?error=Book+not+found');
  exit;
}

$error = '';
$success = false;
$values = $book;

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

  if ($title === '') {
    $error = 'Title is required.';
  }

  // Start with existing cover; allow optional updates
  $cover = $book['cover'];
  
  // If user provided a new cover URL, use it
  if (!empty($coverUrl)) {
    $cover = $coverUrl;
  }
  
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
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($title));
        $slug = trim($slug, '-') ?: 'book';
        $filename = time() . '_' . $slug . '.' . $ext;
        $targetPath = $uploadsDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
          $error = 'Cover upload could not be saved.';
        } else {
          $cover = 'assets/images/uploads/' . $filename;
        }
      }
    }
  }

  if (!$error) {
    $pdo = get_pdo();
    if ($pdo) {
      try {
        $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, price = ?, stock = ?, cover = ?, category = ?, description = ?, featured = ? WHERE id = ?');
        $stmt->execute([$title, $author, $price, $stock, $cover, $category, $description, $featured, $book_id]);
        $success = true;
        $book = find_book($book_id);
        $values = $book;
      } catch (Exception $e) {
        $error = 'Could not save the book to the database. Check your DB config.';
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
        <p class="admin-kicker">Edit Book</p>
        <h1><?= htmlspecialchars($book['title'] ?? 'Book') ?></h1>
        <p class="admin-book-subtitle">Update all details for this book.</p>
      </div>
      <a href="view_books.php" class="admin-btn admin-btn-secondary">← Back</a>
    </div>

    <?php if ($success): ?>
      <div class="admin-alert admin-alert-success">
        ✓ Book updated successfully!
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="admin-alert admin-alert-error">
        ✕ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="admin-book-form">
      <div class="admin-grid">
        <div class="admin-field">
          <label for="title" class="form-label">Title *</label>
          <input type="text" id="title" name="title" class="form-control" required value="<?= htmlspecialchars($values['title'] ?? '') ?>" placeholder="Book title">
        </div>

        <div class="admin-field">
          <label for="author" class="form-label">Author</label>
          <input type="text" id="author" name="author" class="form-control" value="<?= htmlspecialchars($values['author'] ?? '') ?>" placeholder="Author name">
        </div>

        <div class="admin-field">
          <label for="category" class="form-label">Category</label>
          <input type="text" id="category" name="category" class="form-control" value="<?= htmlspecialchars($values['category'] ?? '') ?>" placeholder="Book category">
        </div>

        <div class="admin-field">
          <label for="price" class="form-label">Price (GHS)</label>
          <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($values['price'] ?? '') ?>" placeholder="0.00">
        </div>

        <div class="admin-field">
          <label for="stock" class="form-label">Stock Quantity</label>
          <input type="number" id="stock" name="stock" class="form-control" min="0" value="<?= htmlspecialchars($values['stock'] ?? '') ?>" placeholder="0">
        </div>

        <div class="admin-field admin-field-featured">
          <div class="form-check">
            <input type="checkbox" id="featured" name="featured" class="form-check-input"<?= $values['featured'] ? ' checked' : '' ?>>
            <label for="featured" class="form-check-label">Mark as Featured</label>
          </div>
        </div>

        <div class="admin-field admin-field-full">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" name="description" class="form-control" rows="5" placeholder="Book description"><?= htmlspecialchars($values['description'] ?? '') ?></textarea>
        </div>

        <div class="admin-field admin-field-full">
          <label class="form-label">Cover Image</label>
          <?php if (!empty($values['cover'])): ?>
            <div class="admin-preview-image">
              <img src="<?= htmlspecialchars(resolve_cover_url($values['cover'])) ?>" alt="Current cover">
              <p class="admin-preview-label">Current cover</p>
            </div>
          <?php endif; ?>
        </div>

        <div class="admin-field admin-field-full">
          <label for="cover_file" class="form-label">Upload New Cover</label>
          <input type="file" id="cover_file" name="cover_file" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
          <small class="admin-help">Optional. Supported: JPG, PNG, WebP, GIF. Max 2MB.</small>
        </div>

        <div class="admin-field admin-field-full">
          <label for="cover" class="form-label">Or Paste Cover URL</label>
          <input type="text" id="cover" name="cover" class="form-control" value="<?= htmlspecialchars($values['cover'] ?? '') ?>" placeholder="https://example.com/cover.jpg">
          <small class="admin-help">Direct image URL will be used if no file is uploaded.</small>
        </div>
      </div>

      <div class="admin-actions">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="view_books.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
