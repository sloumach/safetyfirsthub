document.querySelectorAll('.add-to-wishlist').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const courseId = this.dataset.courseId;
        
        fetch('/add-to-wishlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                course_id: courseId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Change heart color
                this.querySelector('i').style.color = 'red';
                
                // Show success message
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    timer: 1500
                });
            } else {
                // Show error message
                Swal.fire({
                    title: 'Notice',
                    text: data.message,
                    icon: 'info',
                    confirmButtonColor: '#3085d6'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Show error message
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong. Please try again.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 6;
    const shopItems = document.querySelectorAll('.shop-item');
    const totalPages = Math.ceil(shopItems.length / itemsPerPage);
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

    // Update the display of shop items
    function updateDisplay() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;

        // Hide all items
        shopItems.forEach(item => {
            item.style.display = 'none';
        });

        // Show only items for current page
        for (let i = start; i < end && i < shopItems.length; i++) {
            shopItems[i].style.display = 'block';
        }

        // Update active state of page numbers
        document.querySelectorAll('.page-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`[data-page="${currentPage}"]`).parentElement.classList.add('active');

        // Update prev/next button states
        document.getElementById('prevPage').parentElement.classList.toggle('disabled', currentPage === 1);
        document.getElementById('nextPage').parentElement.classList.toggle('disabled', currentPage === totalPages);

        // Scroll to top of shop grid
        document.getElementById('shopGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
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