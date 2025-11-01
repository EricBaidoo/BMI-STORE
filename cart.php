
<?php
require_once __DIR__ . '/includes/functions.php';
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// update quantities
	foreach ($_POST['qty'] ?? [] as $id => $q) {
		$q = max(0, (int)$q);
		if ($q === 0) cart_remove($id);
		else $_SESSION['cart'][$id] = $q;
	}
			if ($is_ajax) {
					// Return only the cart table and summary for AJAX
					ob_start();
					?>
					<table class="table cart-table align-middle">
						<thead>
							<tr>
								<th>Book</th>
								<th>Price</th>
								<th>Qty</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach (cart_items() as $it): $b = $it['book']; ?>
							<tr>
								<td>
									<div class="fw-bold"><?= htmlspecialchars($b['title']) ?></div>
									<div class="text-muted small">by <?= htmlspecialchars($b['author']) ?></div>
								</td>
								<td><?= format_price($b['price']) ?></td>
								<td style="max-width:90px">
									<input type="number" name="qty[<?= $b['id'] ?>]" value="<?= $it['qty'] ?>" min="0" class="form-control cart-qty-input">
								</td>
								<td><?= format_price($b['price'] * $it['qty']) ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<div class="cart-summary d-flex align-items-center gap-3">
						<span class="cart-total-label">Total:</span>
						<span class="cart-total-value fw-bold"><?= format_price(cart_total()) ?></span>
						<a class="btn btn-gradient ms-2" href="checkout.php">Checkout</a>
					</div>
					<?php
					$html = ob_get_clean();
					echo $html;
					exit;
			} else {
					header('Location: cart.php');
					exit;
			}
}
$items = cart_items();
?>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="cart-bg py-5">
	<div class="cart-container container">

		<?php
		require_once __DIR__ . '/includes/functions.php';
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    // update quantities
		    foreach ($_POST['qty'] ?? [] as $id => $q) {
		        $q = max(0, (int)$q);
		        if ($q === 0) cart_remove($id);
		        else $_SESSION['cart'][$id] = $q;
		    }
		    header('Location: cart.php');
		    exit;
		}
		$items = cart_items();
		?>
		<?php require_once __DIR__ . '/includes/header.php'; ?>

		<div class="cart-bg py-5">
		  <div class="cart-container container">
		    <div class="row justify-content-center">
		      <div class="col-12 col-lg-10">
		        <div class="cart-card shadow-lg">
		          <h1 class="cart-title mb-4">Your Cart</h1>
		          <?php if (empty($items)): ?>
		            <div class="empty-cart text-center p-5">
		              <p class="lead mb-3">Your cart is empty.</p>
		              <a href="books.php" class="btn btn-gradient">Browse books</a>
		            </div>
		          <?php else: ?>
		            <form method="post">
		              <div class="table-responsive mb-4">
		                <table class="table cart-table align-middle">
		                  <thead>
		                    <tr>
		                      <th>Book</th>
		                      <th>Price</th>
		                      <th>Qty</th>
		                      <th>Subtotal</th>
		                    </tr>
		                  </thead>
		                  <tbody>
		                    <?php foreach ($items as $it): $b = $it['book']; ?>
		                    <tr>
		                      <td>
		                        <div class="fw-bold"><?= htmlspecialchars($b['title']) ?></div>
		                        <div class="text-muted small">by <?= htmlspecialchars($b['author']) ?></div>
		                      </td>
		                      <td><?= format_price($b['price']) ?></td>
		                      <td style="max-width:90px">
		                        <input type="number" name="qty[<?= $b['id'] ?>]" value="<?= $it['qty'] ?>" min="0" class="form-control cart-qty-input">
		                      </td>
		                      <td><?= format_price($b['price'] * $it['qty']) ?></td>
		                    </tr>
		                    <?php endforeach; ?>
		                  </tbody>
		                </table>
		              </div>
		              <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
		                <a class="btn btn-secondary" href="books.php">Continue shopping</a>
		                <div class="cart-summary d-flex align-items-center gap-3">
		                  <span class="cart-total-label">Total:</span>
		                  <span class="cart-total-value fw-bold"><?= format_price(cart_total()) ?></span>
		                  <a class="btn btn-gradient ms-2" href="checkout.php">Checkout</a>
		                  <button type="submit" class="btn btn-outline-danger ms-2">Update cart</button>
		                </div>
		              </div>
		            </form>
		          <?php endif; ?>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>

		<?php require_once __DIR__ . '/includes/footer.php'; ?>
																if (newTable && newSummary) {

																	document.querySelector('.cart-table').innerHTML = newTable.innerHTML;

																	document.querySelector('.cart-summary').innerHTML = newSummary.innerHTML;

																} else {

																	window.location.reload(); // fallback
