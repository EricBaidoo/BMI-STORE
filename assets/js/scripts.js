// Placeholder JS — add interactivity here
console.log('BMI Bookshop scaffold loaded');

// Ensure Bootstrap Carousel is initialized for hero slider
document.addEventListener('DOMContentLoaded', function () {
	var heroCarousel = document.getElementById('heroCarousel');
	if (heroCarousel && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
		var carousel = bootstrap.Carousel.getOrCreateInstance(heroCarousel, {
			interval: 6000,
			ride: 'carousel',
			pause: false,
			wrap: true
		});
	}

	var filtersForm = document.querySelector('.js-books-filters');
	if (filtersForm) {
		filtersForm.addEventListener('change', function (event) {
			var target = event.target;
			if (target && target.tagName === 'SELECT') {
				filtersForm.submit();
			}
		});
	}
});
