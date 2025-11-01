<?php
require_once __DIR__ . '/../includes/functions.php';
// Protect admin pages
require_once __DIR__ . '/_auth.php';
// Simple form that appends to JSON in this scaffold — not production safe.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $books = load_books_from_json();
    $next = count($books) ? end($books)['id'] + 1 : 1;
    $books[] = [
        'id' => $next,
        'slug' => preg_replace('/[^a-z0-9]+/','-',strtolower($_POST['title'])),
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'price' => (float)$_POST['price'],
        'stock' => (int)$_POST['stock'],
        'cover' => $_POST['cover'],
        'category' => $_POST['category'],
        'description' => $_POST['description'],
    ];
    file_put_contents(__DIR__ . '/../data/books.json', json_encode($books, JSON_PRETTY_PRINT));
    header('Location: dashboard.php'); exit;
}
require_once __DIR__ . '/../includes/header.php';
?>
  <h1>Add Book</h1>
  <form method="post" class="row g-3">
    <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Author</label><input name="author" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Price</label><input name="price" class="form-control" type="number" step="0.01"></div>
    <div class="col-md-4"><label class="form-label">Stock</label><input name="stock" class="form-control" type="number"></div>
    <div class="col-md-4"><label class="form-label">Category</label><input name="category" class="form-control"></div>
    <div class="col-12"><label class="form-label">Cover URL</label><input name="cover" class="form-control"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control"></textarea></div>
    <div class="col-12"><button class="btn btn-primary">Add book</button> <a class="btn btn-secondary" href="dashboard.php">Cancel</a></div>
  </form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
