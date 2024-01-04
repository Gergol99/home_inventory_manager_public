<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';

$status = null;
if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Felhasználó meghívása</h1>
    <div class="row justify-content-center" <?php echo $status !== null ? 'hidden' : ''; ?>>
        <form action="" class="col-md-6 col-xl-5" method="post">
            <p class="text-center">A felhasználó kapni fog egy meghívó email-t.</p>
            <div class="mt-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="Felhasználó email címe" required>
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="submit" name="share_invite" class="btn btn-success w-100">
                    Küldés <i class="fa-solid fa-paper-plane"></i>
                </button>
                <a class="btn btn-secondary w-100" href="javascript:history.go(-1)">
                    Vissza <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </form>
    </div>
    <div>
        <p class="text-center" <?php echo $status !== 'success' ? 'hidden' : ''; ?>>
            Sikeresen elküldtük a meghívót a megadott email címre!
        </p>
        <p class="text-center" <?php echo $status !== 'failure' ? 'hidden' : ''; ?>>
            Nem sikerült elküldeni a meghívót mert a megadott email cím nem létezik!
            Kérem győződjön meg róla, hogy helyesen adta meg az email címét!
        </p>
    </div>
</div>

<?php
require_once 'App/View/footer.php';
?>