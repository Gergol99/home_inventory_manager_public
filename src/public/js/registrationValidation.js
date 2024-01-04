const registrationForm = document.getElementById('registration_form');

const registerBtn = document.getElementById('register');
registerBtn.disabled = true;

const username = document.getElementById('username');
const usernameError = document.getElementById('username_error');

const email = document.getElementById('email');
const emailError = document.getElementById('email_error');

const password = document.getElementById('password');
const passwordRe = document.getElementById('password_re');
const passwordError = document.getElementById('password_error');

let isUsernameValid = false;
username.addEventListener('change', () => {
    if (username.value.length < 3 || username.value.length > 20) {
        usernameError.innerHTML = "Min. 3 max. 20 karakter";
        isUsernameValid = false;
    } else if (!/^[a-zA-Z0-9_-]{3,20}$/.test(username.value)) {
        usernameError.innerHTML = "Nem tartalmazhat speciális karaktereket! (Kivéve: _ vagy -)";
        isUsernameValid = false;
    } else {
        usernameError.innerHTML = '';
        isUsernameValid = true;
    }
});

let isEmailValid = false;
email.addEventListener('change', () => {
    if (!/^[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]{1,64}@[a-zA-Z0-9.]{1,192}\.[a-zA-Z0-9]{1,63}$/.test(email.value)) {
        emailError.innerHTML = "Nem megfelelő email cím!";
        isEmailValid = false;
    } else {
        emailError.innerHTML = '';
        isEmailValid = true;
    }
});

let isPasswordValid = false;
password.addEventListener('change', () => {
    if (password.value !== passwordRe.value) {
        passwordError.innerHTML = "A jelszavak nem egyeznek!";
        isPasswordValid = false;
    } else {
        passwordError.innerHTML = '';
        isPasswordValid = true;
    }
});

passwordRe.addEventListener('change', () => {
    if (password.value !== passwordRe.value) {
        passwordError.innerHTML = "A jelszavak nem egyeznek!";
        isPasswordValid = false;
    } else {
        passwordError.innerHTML = '';
        isPasswordValid = true;
    }
});

registrationForm.addEventListener('change', () => {
    if (isUsernameValid && isEmailValid && isPasswordValid) {
        registerBtn.disabled = false;
    } else {
        registerBtn.disabled = true;
    }
});