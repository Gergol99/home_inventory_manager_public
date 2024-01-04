<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Profiladatok</h1>
    <div class="row justify-content-center">
        <form class="col-md-6 col-xl-5" method="post">
            <div class="mt-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="Felhasználónév" value="<?php echo $user->username; ?>" required>
            </div>
            <div class="mt-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $user->email; ?>" required>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" name="update_user_data" class="btn btn-success w-50">
                    Módosítás <i class="fa-solid fa-file-pen"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="row justify-content-center mt-3">
        <form class="col-md-6 col-xl-5" id="password_update_form" method="post">
            <div class="mt-3">
                <input type="password" class="form-control" name="password" id="password" minlength="4" placeholder="Új jelszó" required>
                <p class="text-danger" id="password_error"></p>
            </div>
            <div class="mt-3">
                <input type="password" class="form-control" name="password_re" id="password_re" minlength="4" placeholder="Új jelszó újra" required>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" name="update_password" class="btn btn-success w-50" id="update_password" disabled>
                    Módosítás <i class="fa-solid fa-file-pen"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-xl-5">
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-danger w-50" onclick="openDeleteDialog()">
                    Fiók törlése <i class="fa-solid fa-triangle-exclamation"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<dialog id="delete_profile_dialog">
    <form method="post">
        <h1 class="text-center">Biztos törölni szeretné a fiókját? Ez a változtatás NEM visszavonható!</h1>
        <h2 class="text-center">A fiókhoz tartozó összes háztartás is törlődni fog!</h2>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <button type="submit" name="delete_profile" class="btn btn-danger w-100">
                Törlés <i class="fa-solid fa-trash"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<script>
    const passwordUpdateForm = document.getElementById('password_update_form');

    const updatePasswordBtn = document.getElementById('update_password');
    updatePasswordBtn.disabled = true;

    const password = document.getElementById('password');
    const passwordRe = document.getElementById('password_re');
    const passwordError = document.getElementById('password_error');

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

    passwordUpdateForm.addEventListener('change', () => {
        if (isPasswordValid) {
            updatePasswordBtn.disabled = false;
        } else {
            updatePasswordBtn.disabled = true;
        }
    });


    function openDeleteDialog() {
        const deleteProfileDialog = document.getElementById('delete_profile_dialog');
        deleteProfileDialog.showModal();
    }
</script>

<?php
require_once 'App/View/footer.php';
?>