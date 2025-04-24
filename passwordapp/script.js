document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const inputField = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (inputField.type === 'password') {
                inputField.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                inputField.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
    
    // Initialize clipboard.js for copy functionality
    const clipboard = new ClipboardJS('.copy-password');
    clipboard.on('success', function(e) {
        // Change button text temporarily
        const originalHTML = e.trigger.innerHTML;
        e.trigger.innerHTML = '<i class="bi bi-check"></i>';
        
        setTimeout(function() {
            e.trigger.innerHTML = originalHTML;
        }, 1500);
        
        e.clearSelection();
    });
    
    // Password generator
    const generateButtons = document.querySelectorAll('.generate-password');
    generateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const passwordField = this.closest('.input-group').querySelector('input');
            passwordField.value = generateStrongPassword();
            passwordField.type = 'text';
            const toggleButton = this.closest('.input-group').querySelector('.toggle-password i');
            toggleButton.classList.remove('bi-eye');
            toggleButton.classList.add('bi-eye-slash');
        });
    });
    
    // Function to generate a strong password
    function generateStrongPassword() {
        const length = 16;
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
        let password = '';
        
        // Ensure at least one of each character type
        password += getRandomChar('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        password += getRandomChar('abcdefghijklmnopqrstuvwxyz');
        password += getRandomChar('0123456789');
        password += getRandomChar('!@#$%^&*()-_=+');
        
        // Fill the rest of the password
        for (let i = password.length; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        
        // Shuffle the password
        return shuffleString(password);
    }
    
    function getRandomChar(charset) {
        return charset.charAt(Math.floor(Math.random() * charset.length));
    }
    
    function shuffleString(string) {
        const array = string.split('');
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array.join('');
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});