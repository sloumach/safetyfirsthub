// Handle avatar upload
document.getElementById('avatar-upload').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Make the avatar preview clickable to trigger file input
document.querySelector('.avatar-preview').addEventListener('click', function () {
    document.getElementById('avatar-upload').click();
});

// Handle cancel button
document.getElementById('cancel-button').addEventListener('click', function () {
    const confirmCancel = confirm('Are you sure you want to discard changes?');
    if (confirmCancel) {
        alert('Changes discarded.');
        // Reset the form fields
        document.getElementById('profile-form').reset();
        // Reset avatar preview to default
        document.getElementById('avatar-preview').src = 'default-avatar.jpg';
        // Clear the file input
        document.getElementById('avatar-upload').value = '';
    }
});

// Handle form submission
document.getElementById('profile-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Collect form data
    const fullName = document.getElementById('full-name').value;
    const jobRole = document.getElementById('job-role').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const company = document.getElementById('company').value;
    const website = document.getElementById('website').value;
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const postalCode = document.getElementById('postal-code').value;
    const country = document.getElementById('country').value;
    const avatarFile = document.getElementById('avatar-upload').files[0];

    // Validate required fields
    if (!fullName || !email || !phone) {
        alert('Please fill out all required fields: Full Name, Email, and Phone Number.');
        return;
    }

    // Prepare form data to send to the server
    const formData = new FormData();
    formData.append('fullName', fullName);
    formData.append('jobRole', jobRole);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('company', company);
    formData.append('website', website);
    formData.append('address', address);
    formData.append('city', city);
    formData.append('postalCode', postalCode);
    formData.append('country', country);
    if (avatarFile) {
        formData.append('avatar', avatarFile);
    }

    // Send data to the server (using Fetch API)
    fetch('/update-profile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Profile updated successfully!');
            // Update the avatar preview with the new URL from the server
            if (result.avatarUrl) {
                document.getElementById('avatar-preview').src = result.avatarUrl;
            }
        } else {
            alert('Failed to update profile. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});