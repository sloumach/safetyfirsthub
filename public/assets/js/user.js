$(document).ready(function() {
    $('#paymentTable').DataTable({
        pageLength: 4,
        lengthMenu: [4, 8, 12],
        language: {
            lengthMenu: "Show _MENU_ items per page",
            info: "",
            infoEmpty: "",
            search: "Search:",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            zeroRecords: "No data available in table"
        },
        columnDefs: [
            { orderable: false, targets: [2] }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12"p>>'
    });
});

function switchTab(tab) {
    // Hide all tab contents
    document.querySelectorAll('.ud-tab-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.ud-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab content and activate tab
    if (tab === 'profile') {
        document.getElementById('profile-tab').style.display = 'block';
        document.querySelector('.ud-tab:first-child').classList.add('active');
    } else {
        document.getElementById('payment-tab').style.display = 'block';
        document.querySelector('.ud-tab:last-child').classList.add('active');
    }
};