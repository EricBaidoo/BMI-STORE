<?php
require_once __DIR__ . '/includes/functions.php';
$page_css = 'index.css';
require_once __DIR__ . '/includes/header.php';
?>


<main class="p-0">
	<div id="heroCarousel" class="carousel slide hero-slider" data-bs-ride="carousel" data-bs-interval="6000">
		<div class="carousel-inner">
			<!-- Slide 1 -->
			<div class="carousel-item hero-slide active" style="background-image: url('assets/images/477983923_1258677485680214_2499823974197020767_n.jpg');">
				<div class="hero-content text-center">
					<h1 class="hero-title">Meet Rev. F. D. Yalley</h1>
					<p class="hero-subtitle">Discover the inspiring works and faith-filled journey of our featured author.</p>
					<a href="#authors" class="btn btn-primary hero-btn">Explore Books</a>
				</div>
			</div>
			<!-- Slide 2 -->
			<div class="carousel-item hero-slide" style="background-image: url('assets/images/41fSSwll0vL._SY445_SX342_.jpg');">
				<div class="hero-content text-center">
					<h1 class="hero-title">New Book Arrivals</h1>
					<p class="hero-subtitle">Be the first to read the latest releases and trending titles.</p>
					<a href="#new" class="btn btn-primary hero-btn">Browse New Books</a>
				</div>
			</div>
			<!-- Slide 3 -->
			<div class="carousel-item hero-slide" style="background-image: url('assets/images/618UBIbrB-L._UF1000,1000_QL80_.jpg');">
				<div class="hero-content text-center">
					<h1 class="hero-title">Inspiration for Every Reader</h1>
					<p class="hero-subtitle">Unlock new worlds, spark your imagination, and grow in faith—one page at a time.</p>
					<a href="#motivation" class="btn btn-primary hero-btn">Get Inspired</a>
				</div>
			</div>
		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>

<!-- Featured Books Section as Carousel -->
<section class="featured-books-section">
	<div class="container section-content">
		<h2 class="section-title text-center">Featured Books</h2>
		<div id="featuredBooksCarousel" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner">
				<!-- Slide 1: Book 1, Book 2, Book 3 (responsive) -->
				<div class="carousel-item active">
					<div class="row justify-content-center">
						<div class="col-12 col-md-6 col-lg-4 mb-4">
							<div class="card featured-book-card h-100 text-center">
								<img src="assets/images/41fSSwll0vL._SY445_SX342_.jpg" class="card-img-top mx-auto" alt="Book Cover 1">
								<div class="card-body">
									<h5 class="card-title">Workshop to Showroom</h5>
									<p class="card-author text-muted mb-2">Rev. F. D. Yalley</p>
									<p class="card-text">A practical guide to transforming your vision into reality, rooted in faith and leadership.</p>
									<div class="featured-book-price mb-2">₵45.00</div>
									<a href="#" class="btn btn-primary btn-sm">View Details</a>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4 mb-4 d-none d-md-block">
							<div class="card featured-book-card h-100 text-center">
								<img src="assets/images/51smeFzou3L._SY425_.jpg" class="card-img-top mx-auto" alt="Book Cover 2">
								<div class="card-body">
									<h5 class="card-title">Faith for Today</h5>
									<p class="card-author text-muted mb-2">Jane Doe</p>
									<p class="card-text">Daily inspiration and encouragement for your spiritual journey.</p>
									<div class="featured-book-price mb-2">₵38.00</div>
									<a href="#" class="btn btn-primary btn-sm">View Details</a>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4 mb-4 d-none d-lg-block">
							<div class="card featured-book-card h-100 text-center">
								<img src="assets/images/618UBIbrB-L._UF1000,1000_QL80_.jpg" class="card-img-top mx-auto" alt="Book Cover 3">
								<div class="card-body">
									<h5 class="card-title">Children of Grace</h5>
									<p class="card-author text-muted mb-2">Samuel Mensah</p>
									<p class="card-text">A heartwarming story for young readers about kindness, faith, and hope.</p>
									<div class="featured-book-price mb-2">₵29.00</div>
									<a href="#" class="btn btn-primary btn-sm">View Details</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- If you add more books, add more slides here -->
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#featuredBooksCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#featuredBooksCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		</div>
	</div>
</section>
<div class="container text-center section-content">
  <a href="books.php" class="btn btn-outline-primary btn-lg view-all-books-btn">View All Books</a>
</div>

<!-- Author Spotlight Section -->
<section class="author-spotlight-section">
  <div class="container section-content">
    <div class="row align-items-center justify-content-center flex-md-row flex-column">
      <div class="col-12 col-md-7 d-flex flex-column justify-content-center">
        <h2 class="section-title">Author Spotlight</h2>
        <div class="d-block d-md-none text-center my-3">
          <img src="assets/images/author photo.JPG" alt="Rev. F. D. Yalley" class="author-photo img-fluid rounded-circle shadow">
        </div>
        <h3 class="author-name">Rev Francis Duane Yalley</h3>
        <p class="author-bio">
          F. D. Yalley, Founder and Senior Pastor of Bridge Ministries International, is affectionately called the REPAIRER. 
          His dynamic, lightning rod teaching style inspires a deep love and desire to serve God.
        </p>
        <p class="author-bio">
          Pastor Yalley’s messages are direct and rooted in scripture, motivating listeners
           to strengthen their faith and pursue a purposeful spiritual life.
        </p>
        <p class="author-bio">
          He makes serving God accessible and joyful, fostering a community where devotion and service are at the heart of the ministry.
        </p>
      </div>
      <div class="col-12 col-md-4 text-center mb-3 mb-md-0 d-none d-md-flex flex-column align-items-center justify-content-center">
        <img src="assets/images/author photo.JPG" alt="Rev. F. D. Yalley" class="author-photo img-fluid rounded-circle shadow">
      </div>
    </div>
  </div>
</section>



</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
