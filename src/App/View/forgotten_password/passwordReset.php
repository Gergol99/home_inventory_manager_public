<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Új jelszó megadás</h1>
    <div class="row justify-content-center">
        <form class="col-md-6 col-xl-5" id="reset_form" method="post">
            <div>
                <input type="password" class="form-control" name="password" id="password" placeholder="Új jelszó" required>
                <p class="text-danger" id="password_error"></p>
            </div>
            <div class="mt-3">
                <input type="password" class="form-control" name="password_re" id="password_re" placeholder="Új jelszó újra" required>
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="submit" name="reset_password" id="reset_password" class="btn btn-success w-100" disabled>
                    Mentés <i class="fa-solid fa-plus"></i>
                </button>
                <a class="btn btn-secondary w-100" href="?controller=index&action=login">
                    Vissza a főoldalra <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const resetForm = document.getElementById('reset_form');
    const password = document.getElementById('password');
    const passwordRe = document.getElementById('password_re');
    const passwordError = document.getElementById('password_error');
    const resetPasswordBtn = document.getElementById('reset_password');

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

    resetForm.addEventListener('change', (e) => {
        if (isPasswordValid) {
            resetPasswordBtn.disabled = false;
        } else {
            resetPasswordBtn.disabled = true;
        }
    });
</script>

<?php
require_once 'App/View/footer.php';
?>