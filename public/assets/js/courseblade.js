document.addEventListener('DOMContentLoaded', function() {
    // Constants
    const ITEMS_PER_PAGE = 6;

    // Elements
    const filterButtons = document.querySelectorAll('.filter-btn');
    const courseItems = Array.from(document.querySelectorAll('.course-item'));
    const pageNumbers = document.getElementById('pageNumbers');
    const prevButton = document.getElementById('prevPage');
    const nextButton = document.getElementById('nextPage');

    // State
    let currentCategory = 'all';
    let currentPage = 1;

    // Initialize
    function init() {
        // Add click listeners to filter buttons
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Update category and reset page
                currentCategory = button.getAttribute('data-category');
                currentPage = 1;

                // Update display
                updateDisplay();
            });
        });

        // Add click listeners to pagination buttons
        prevButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                updateDisplay();
            }
        });

        nextButton.addEventListener('click', (e) => {
            e.preventDefault();
            const totalPages = Math.ceil(getVisibleItems().length / ITEMS_PER_PAGE);
            if (currentPage < totalPages) {
                currentPage++;
                updateDisplay();
            }
        });

        // Initial display
        updateDisplay();
    }

    // Get visible items based on current category
    function getVisibleItems() {
        return courseItems.filter(item => {
            const itemCategory = item.getAttribute('data-category');
            return currentCategory === 'all' || itemCategory === currentCategory;
        });
    }

    // Update pagination numbers
    function updatePagination() {
        const visibleItems = getVisibleItems();
        const totalPages = Math.ceil(visibleItems.length / ITEMS_PER_PAGE);

        // Clear existing page numbers
        pageNumbers.innerHTML = '';

        // Create page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>`;

            // Add click event
            li.querySelector('a').addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                updateDisplay();
            });

            pageNumbers.appendChild(li);
        }

        // Update prev/next button states
        prevButton.parentElement.classList.toggle('disabled', currentPage === 1);
        nextButton.parentElement.classList.toggle('disabled', currentPage === totalPages);
    }

    // Update course items display
    function updateCourseDisplay() {
        const visibleItems = getVisibleItems();
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        const end = start + ITEMS_PER_PAGE;

        // Hide all items first
        courseItems.forEach(item => {
            item.style.cssText = 'display: none !important';
        });

        // Show only items for current page
        visibleItems.slice(start, end).forEach(item => {
            item.style.cssText = 'display: block !important';
        });

        // Scroll to top of courses section
        const coursesSection = document.querySelector('.courses-area');
        if (coursesSection) {
            coursesSection.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Update both pagination and course display
    function updateDisplay() {
        updatePagination();
        updateCourseDisplay();
    }

    // Initialize the page
    init();
});
