
    $(document).ready(function() {
        // Initialize DataTable
        $('#coursesTable').DataTable();

        // Edit button click handler
        $('.edit-btn').click(function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let price = $(this).data('price');
            let category = $(this).data('category');
            let totalVideos = $(this).data('total_videos');
            let shortDescription = $(this).data('short_description');
            let description = $(this).data('description');

            $('#edit-course-id').val(id);
            $('#edit-name').val(name);
            $('#edit-price').val(price);
            $('#edit-category').val(category);
            $('#edit-total-videos').val(totalVideos);
            $('#edit-short-description').val(shortDescription);
            $('#edit-description').val(description);

            $('#editCourseForm').attr('action', '/admin/course/update/' + id);
            $('#editCourseModal').modal('show');
        });
    });
