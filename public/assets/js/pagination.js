document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 6;
    const courseItems = document.querySelectorAll('.course-item');
    const totalPages = Math.ceil(courseItems.length / itemsPerPage);
    let currentPage = 1;

    // Initialize pagination
    function initPagination() {
        const pageNumbers = document.getElementById('pageNumbers');
        pageNumbers.innerHTML = '';

        // Create page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
            pageNumbers.appendChild(li);

            // Add click event to page numbers
            li.querySelector('a').addEventListener('click', (e) => {
                e.preventDefault();
                currentPage = i;
                updateDisplay();
            });
        }

        updateDisplay();
    }

    // Update the display of courses
    function updateDisplay() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Hide all courses
        courseItems.forEach(item => {
            item.style.display = 'none';
        });

        // Show only courses for current page
        for (let i = start; i < end && i < courseItems.length; i++) {
            courseItems[i].style.display = 'block';
        }

        // Update active state of page numbers
        document.querySelectorAll('.page-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`[data-page="${currentPage}"]`).parentElement.classList.add('active');

        // Update prev/next button states
        document.getElementById('prevPage').parentElement.classList.toggle('disabled', currentPage === 1);
        document.getElementById('nextPage').parentElement.classList.toggle('disabled', currentPage === totalPages);
    }

    // Previous page button
    document.getElementById('prevPage').addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            updateDisplay();
        }
    });

    // Next page button
    document.getElementById('nextPage').addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            updateDisplay();
        }
    });

    // Initialize the pagination
    initPagination();
}); 