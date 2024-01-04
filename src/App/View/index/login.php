<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Bejelentkezés</h1>
    <div class="row justify-content-center">
        <form action="" class="col-md-6 col-xl-5" method="post">
            <p class="text-danger"><?php echo $loginError; ?></p>
            <div class="mt-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" aria-label="Email" required>
            </div>
            <div class="mt-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Jelszó" aria-label="Password" required>
            </div>
            <div class="row justify-content-center mt-3">
                <button type="submit" name="login" class="btn btn-success col-8 col-md-6">
                    Bejelentkezés <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </div>
            <div class="d-flex justify-content-center">
                <div class="mt-3 form-check form-switch">
                    <input type="checkbox" class="form-check-input" role="switch" name="remember_me" id="remember_me">
                    <label for="remember_me" class="form-check-label">Emlékezz rám!</label>
                </div>
            </div>
            <div class="text-center mt-4">
                <p><a class="link-primary" href="?controller=forgotten_password&action=request_reset">Elfelejtettem a jelszavamat.</a></p>
                <p><a class="link-primary" href="?controller=index&action=register">Még nem regisztráltam.</a></p>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'App/View/footer.php';
?>