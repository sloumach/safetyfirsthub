document.getElementById('remember').addEventListener('change', function() {
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