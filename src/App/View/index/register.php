<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Regisztráció</h1>
    <div class="row justify-content-center">
        <form action="" class="col-md-6 col-xl-5" id="registration_form" method="post">
            <div>
                <input type="text" class="form-control" name="username" id="username" placeholder="Felhasználónév *" maxlength="20" aria-label="Username" required>
                <p class="text-danger" id="username_error"><?php echo $usernameError; ?></p>
            </div>
            <div class="mt-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email *" aria-label="Email" required>
                <p class="text-danger" id="email_error"><?php echo $emailError; ?></p>
            </div>
            <div class="mt-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Jelszó *" aria-label="Password" required>
                <p class="text-danger" id="password_error"></p>
            </div>
            <div class="mt-3">
                <input type="password" class="form-control" name="password_re" id="password_re" placeholder="Jelszó újra *" aria-label="Password repeat" required>
            </div>
            <div class="row justify-content-center mt-3">
                <button type="submit" class="btn btn-success col-8 col-md-6" name="register" id="register">
                    Regisztráció <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<script src="public/js/registrationValidation.js"></script>
<?php
require_once 'App/View/footer.php';
?>