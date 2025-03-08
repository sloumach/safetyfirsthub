document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 6;
    const courseItems = Array.from(document.querySelectorAll('.course-item'));
    
    if (courseItems.length === 0) return;

    const totalPages = Math.ceil(courseItems.length / itemsPerPage);
    let currentPage = 1;

    function createPagination() {
        const pageNumbers = document.getElementById('pageNumbers');
        pageNumbers.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>`;
            
            // Add click event listener
            li.querySelector('a').addEventListener('click', function(e) {
                e.preventDefault();
                changePage(i);
            }, { passive: false });

            pageNumbers.appendChild(li);
        }
    }

    function changePage(page) {
        currentPage = page;
        
        // Hide all courses first
        courseItems.forEach(item => {
            item.style.cssText = 'display: none !important';
        });

        // Show only courses for current page
        const start = (currentPage - 1) * itemsPerPage;
        const end = Math.min(start + itemsPerPage, courseItems.length);
        
        for (let i = start; i < end; i++) {
            if (courseItems[i]) {
                courseItems[i].style.cssText = 'display: block !important';
            }
        }

        // Update active states
        updateActiveStates();

        // Scroll to top of courses section
        const coursesSection = document.querySelector('.courses-area');
        if (coursesSection) {
            coursesSection.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function updateActiveStates() {
        // Update page number active states
        document.querySelectorAll('#pageNumbers .page-item').forEach(item => {
            const pageNum = parseInt(item.querySelector('a').getAttribute('data-page'));
            item.classList.toggle('active', pageNum === currentPage);
        });

        // Update prev/next buttons
        const prevButton = document.getElementById('prevPage').parentElement;
        const nextButton = document.getElementById('nextPage').parentElement;
        
        prevButton.classList.toggle('disabled', currentPage === 1);
        nextButton.classList.toggle('disabled', currentPage === totalPages);
    }

    // Prev/Next button handlers
    document.getElementById('prevPage').addEventListener('click', function(e) {
        e.preventDefault();
        if (currentPage > 1) {
            changePage(currentPage - 1);
        }
    });

    document.getElementById('nextPage').addEventListener('click', function(e) {
        e.preventDefault();
        if (currentPage < totalPages) {
            changePage(currentPage + 1);
        }
    });

    // Force re-pagination on window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            changePage(currentPage);
        }, 250);
    });

    // Initialize pagination
    createPagination();
    changePage(1);
}); 