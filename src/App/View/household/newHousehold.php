<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Új háztartás hozzáadása</h1>
    <div class="row justify-content-center">
        <form action="" class="col-md-6 col-xl-5" method="post">
            <div class="mt-3">
                <input type="text" class="form-control" name="name" id="name" placeholder="Háztartás neve" required>
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="submit" name="save" class="btn btn-success w-100">
                    Mentés <i class="fa-solid fa-plus"></i>
                </button>
                <a class="btn btn-secondary w-100" href="javascript:history.go(-1)">
                    Vissza <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'App/View/footer.php';
?>