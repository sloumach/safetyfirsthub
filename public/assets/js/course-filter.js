document.addEventListener('DOMContentLoaded', function() {
    // Get all filter buttons and course items
    const filterButtons = document.querySelectorAll('.filter-btn');
    const courseItems = document.querySelectorAll('.course-item');
    const coursesContainer = document.querySelector('.courses-container');
    const owlCarousel = $('.courses-slider-two');

    // Add click event listener to each filter button
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get selected category
            const selectedCategory = this.getAttribute('data-category');

            if (owlCarousel.length) {
                // If using Owl Carousel
                owlCarousel.trigger('destroy.owl.carousel'); // Destroy current carousel

                // First, remove all items from carousel
                owlCarousel.empty();

                // Filter and only append visible items
                const visibleItems = [];
                courseItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    if (selectedCategory === 'all' || selectedCategory === itemCategory) {
                        visibleItems.push(item.cloneNode(true));
                    }
                });

                // Append only visible items back to carousel
                visibleItems.forEach(item => {
                    owlCarousel.append(item);
                });

                // Reinitialize Owl Carousel with visible items
                owlCarousel.owlCarousel({
                    loop: true,
                    margin: 30,
                    nav: false,
                    dots: true,
                    autoplay: true,
                    smartSpeed: 1000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: { items: 1 },
                        576: { items: 2 },
                        768: { items: 2 },
                        992: { items: 3 },
                        1200: { items: 3 }
                    }
                });
            } else {
                // If not using Owl Carousel
                const container = courseItems[0].parentElement;
                container.style.display = 'flex';
                container.style.flexWrap = 'wrap';
                container.style.gap = '30px';
                container.style.justifyContent = 'center';

                courseItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    if (selectedCategory === 'all' || selectedCategory === itemCategory) {
                        item.style.display = '';
                        item.style.flex = '0 0 auto';
                        item.style.maxWidth = '350px';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });
    });
}); 