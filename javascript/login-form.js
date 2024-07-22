  const loginForm = document.getElementById('loginForm');
        const registrationForm = document.getElementById('registrationForm');

        registrationForm.style.display = "none";


        function showRegistrationForm() {
            registrationForm.style.display = "";
            loginForm.style.display = "none";
        }

        function showLoginForm() {
            registrationForm.style.display = "none";
            loginForm.style.display = "";
        }

        function sendVerificationCode() {
            const registrationElements = document.querySelectorAll('.registration');

            registrationElements.forEach(element => {
                element.style.display = 'none';
            });

            const verification = document.querySelector('.verification');
            if (verification) {
                verification.style.display = 'none';
            }
        }