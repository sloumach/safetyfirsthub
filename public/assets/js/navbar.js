document.addEventListener('DOMContentLoaded', function() {
    const profileDropdown = document.querySelector('.nav-profile-dropdown');
    const dropdownMenu = document.getElementById('profileDropdown');

    function toggleProfileMenu(e) {
        e.preventDefault();
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
        profileDropdown.classList.toggle('show');
    }

    // Add click event to profile link
    const profileLink = profileDropdown.querySelector('.nav-link');
    if (profileLink) {
        profileLink.addEventListener('click', toggleProfileMenu);
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!profileDropdown.contains(e.target)) {
            dropdownMenu.classList.remove('show');
            profileDropdown.classList.remove('show');
        }
    });

    // Close dropdown when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdownMenu.classList.remove('show');
            profileDropdown.classList.remove('show');
        }
    });
});