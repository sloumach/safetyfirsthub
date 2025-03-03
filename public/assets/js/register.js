document.getElementById('show-password').addEventListener('change', function() {
    // Get both password fields
    var passwordField = document.querySelector('input[name="password"]');
    var confirmPasswordField = document.querySelector('input[name="password_confirmation"]');
    
    // Toggle type between "password" and "text"
    if(this.checked) {
        passwordField.type = 'text';
        confirmPasswordField.type = 'text';
    } else {
        passwordField.type = 'password';
        confirmPasswordField.type = 'password';
    }
});

// Add error handling in case the element isn't found
document.addEventListener('DOMContentLoaded', function() {
    const showPasswordCheckbox = document.getElementById('show-password');
    if (showPasswordCheckbox) {
        showPasswordCheckbox.addEventListener('change', function() {
            var passwordField = document.querySelector('input[name="password"]');
            var confirmPasswordField = document.querySelector('input[name="password_confirmation"]');
            
            const type = this.checked ? 'text' : 'password';
            
            if (passwordField) passwordField.type = type;
            if (confirmPasswordField) confirmPasswordField.type = type;
        });
    }
});

