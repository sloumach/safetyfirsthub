$(document).on('click', '.edit-btn', function() {
    let courseId = $(this).data('id');
    let name = $(this).data('name');
    let price = $(this).data('price');
    let category = $(this).data('category');
    let totalVideos = $(this).data('total_videos');
    let shortDescription = $(this).data('short_description');
    let description = $(this).data('description');
    // Remplir les champs du formulaire dans le modal
    $('#edit-course-id').val(courseId);
    $('#edit-name').val(name);
    $('#edit-price').val(price);
    $('#edit-category').val(category);
    $('#edit-total-videos').val(totalVideos);
    $('#edit-short-description').val(shortDescription);
    $('#edit-description').val(description);
    // Définir l'action du formulaire avec l'ID du cours
    $('#editCourseForm').attr('action', '/admin/course/update/' + courseId);
    // Ouvrir le modal
    $('#editCourseModal').modal('show');
});

document.addEventListener("DOMContentLoaded", function() {
    let salesCtx = document.getElementById('salesChart').getContext('2d');
    let revenueCtx = document.getElementById('revenueChart').getContext('2d');

    let salesData = window.salesByCourse;
    let revenueData = window.revenueByCourse;
    let labels = [];
    let salesDatasets = [];
    let revenueDatasets = [];

    Object.keys(salesData).forEach((course, index) => {
        let courseData = salesData[course].map(sale => sale.total_sales);
        let months = salesData[course].map(sale => sale.month);

        if (labels.length === 0) labels = months;

        salesDatasets.push({
            label: course,
            data: courseData,
            borderColor: `hsl(${index * 60}, 70%, 50%)`,
            backgroundColor: `hsl(${index * 60}, 70%, 70%)`,
            fill: false,
            tension: 0.3
        });

        revenueDatasets.push({
            label: course,
            data: revenueData[course].map(rev => rev.total_revenue),
            borderColor: `hsl(${index * 60}, 70%, 50%)`,
            backgroundColor: `hsl(${index * 60}, 70%, 70%)`,
            fill: false,
            tension: 0.3
        });
    });

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels,
            datasets: salesDatasets
        }
    });
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels,
            datasets: revenueDatasets
        }
    });
});