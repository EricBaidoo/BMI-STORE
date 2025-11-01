<?php
require_once __DIR__ . '/../includes/functions.php';
// Protect admin pages
require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../includes/header.php';
?>
  <h1>Admin Dashboard</h1>
  <p>Use the links below to manage the store (this area is NOT protected in this scaffold).</p>
  <ul>
    <li><a href="add_book.php">Add book</a></li>
    <li><a href="view_orders.php">View orders</a></li>
    <li><a href="view_subscribers.php">View subscribers</a></li>
    <li><a href="change_password.php">Change admin password</a></li>
  </ul>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
