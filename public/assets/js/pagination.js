document.addEventListener('DOMContentLoaded', () => {
    const itemsPerPage = 6;
    const courses = document.querySelectorAll('.course-item');
    const totalPages = Math.ceil(courses.length / itemsPerPage);
    let currentPage = 1;

    const updateDisplay = () => {
        const start = (currentPage - 1) * itemsPerPage;
        const end = Math.min(start + itemsPerPage, courses.length);
        
        // Update course visibility
        courses.forEach((course, index) => {
            course.style.display = (index >= start && index < end) ? 'block' : 'none';
        });

        // Update pagination buttons
        document.querySelectorAll('.page-numbers').forEach(btn => {
            btn.classList.toggle('active', parseInt(btn.dataset.page) === currentPage);
        });
        
        // Update prev/next buttons
        document.getElementById('prevPage').parentElement.classList.toggle('disabled', currentPage === 1);
        document.getElementById('nextPage').parentElement.classList.toggle('disabled', currentPage === totalPages);
    };

    // Generate pagination numbers
    const pageNumbers = document.getElementById('pageNumbers');
    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.className = `page-link page-numbers ${i === 1 ? 'active' : ''}`;
        button.dataset.page = i;
        button.textContent = i;
        button.onclick = () => {
            currentPage = i;
            updateDisplay();
        };
        pageNumbers.appendChild(button);
    }

    // Add event listeners for prev/next
    document.getElementById('prevPage').onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            updateDisplay();
        }
    };

    document.getElementById('nextPage').onclick = () => {
        if (currentPage < totalPages) {
            currentPage++;
            updateDisplay();
        }
    };

    // Initial display
    updateDisplay();
}); 