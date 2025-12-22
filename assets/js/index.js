function toggleForm(event) {
    event.preventDefault();
    const loginForm = document.getElementById('loginForm');
    const registrationForm = document.getElementById('registrationForm');
    
    loginForm.classList.toggle('active');
    registrationForm.classList.toggle('active');
}