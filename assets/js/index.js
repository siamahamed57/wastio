// Toggle between login and registration forms
function toggleForm(event) {
    event.preventDefault();
    const loginForm = document.getElementById('loginForm');
    const registrationForm = document.getElementById('registrationForm');

    loginForm.classList.toggle('active');
    registrationForm.classList.toggle('active');
}

// Toggle password visibility
function togglePassword(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
const passwordInput = document.getElementById('regPassword');
if (passwordInput) {
    passwordInput.addEventListener('input', function () {
        const password = this.value;
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        let strength = 0;

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        // Remove all classes
        strengthFill.className = 'strength-fill';
        strengthText.className = 'strength-text';

        if (password.length === 0) {
            strengthText.textContent = 'Password strength';
        } else if (strength <= 1) {
            strengthFill.classList.add('weak');
            strengthText.classList.add('weak');
            strengthText.textContent = 'Weak password';
        } else if (strength <= 3) {
            strengthFill.classList.add('medium');
            strengthText.classList.add('medium');
            strengthText.textContent = 'Medium password';
        } else {
            strengthFill.classList.add('strong');
            strengthText.classList.add('strong');
            strengthText.textContent = 'Strong password';
        }
    });
}

// Form validation
function validateRegistration() {
    let isValid = true;

    // Name validation
    const name = document.getElementById('regName');
    const nameError = document.getElementById('nameError');
    if (name.value.trim().length < 3) {
        nameError.textContent = 'Name must be at least 3 characters';
        name.classList.add('error');
        isValid = false;
    } else {
        nameError.textContent = '';
        name.classList.remove('error');
        name.classList.add('success');
    }

    // Email validation
    const email = document.getElementById('regEmail');
    const emailError = document.getElementById('emailError');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        emailError.textContent = 'Please enter a valid email';
        email.classList.add('error');
        isValid = false;
    } else {
        emailError.textContent = '';
        email.classList.remove('error');
        email.classList.add('success');
    }

    // Phone validation
    const phone = document.getElementById('regPhone');
    const phoneError = document.getElementById('phoneError');
    const phonePattern = /^[0-9]{10,15}$/;
    if (!phonePattern.test(phone.value)) {
        phoneError.textContent = 'Phone must be 10-15 digits';
        phone.classList.add('error');
        isValid = false;
    } else {
        phoneError.textContent = '';
        phone.classList.remove('error');
        phone.classList.add('success');
    }

    // Role validation
    const role = document.getElementById('regRole');
    const roleError = document.getElementById('roleError');
    if (role.value === '') {
        roleError.textContent = 'Please select a role';
        role.classList.add('error');
        isValid = false;
    } else {
        roleError.textContent = '';
        role.classList.remove('error');
        role.classList.add('success');
    }

    // Password validation
    const password = document.getElementById('regPassword');
    const passwordError = document.getElementById('passwordError');
    if (password.value.length < 8) {
        passwordError.textContent = 'Password must be at least 8 characters';
        password.classList.add('error');
        isValid = false;
    } else {
        passwordError.textContent = '';
        password.classList.remove('error');
        password.classList.add('success');
    }

    return isValid;
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function () {
    const inputs = ['regName', 'regEmail', 'regPhone', 'regRole', 'regPassword'];
    inputs.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('blur', validateRegistration);
        }
    });
});