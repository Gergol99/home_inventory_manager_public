<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';

$status = null;
if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Jelszó visszaállítás kérése</h1>
    <div class="row justify-content-center" <?php echo $status !== null ? 'hidden' : ''; ?>>
        <form class="col-md-6 col-xl-5" method="post">
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="submit" name="request_reset" class="btn btn-success w-100">
                    Küldés <i class="fa-solid fa-plus"></i>
                </button>
                <a class="btn btn-secondary w-100" href="javascript:history.go(-1)">
                    Vissza <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </form>
    </div>
    <div>
        <p class="text-center" <?php echo $status !== 'success' ? 'hidden' : ''; ?>>
            Küldtünk egy üzenetet a megadott email címre mely tartalmaz egy helyreállító linket! 
            Amennyiben nem kapta meg az üzenetet, győződjön meg róla, hogy helyesen adtad-e meg.
        </p>
        <p class="text-center" <?php echo $status !== 'failure' ? 'hidden' : ''; ?>>
            Nem sikerült elküldeni az üzenetet mert a megadott email cím nem szerepel az adatbázisban!
            Kérem győződjön meg róla, hogy helyesen adta meg az email címét!
        </p>
    </div>
</div>

<?php
require_once 'App/View/footer.php';
?>