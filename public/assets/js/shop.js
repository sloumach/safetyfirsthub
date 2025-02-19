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