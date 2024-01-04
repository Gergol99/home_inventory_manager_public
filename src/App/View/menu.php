<?php
@session_start();
function hideIfGuest() {
    echo $_SESSION['usr_level'] === "guest" ? "hidden" : "";
}

function hideIfUser() {
    echo $_SESSION['usr_level'] === "user" ? "hidden" : "";
}

function getNavURL() {
    echo $_SESSION['usr_level'] === "guest" ? "?controller=index&action=login" : "?controller=household&action=list";
}
?>

<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ms-md-5" href="<?php getNavURL(); ?>" title="Háztartási leltárprogram">Háztartási Leltárprogram</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item" <?php hideIfGuest(); ?>>
                        <a class="nav-link" href="?controller=household&action=list">Háztartások <i class="fa-solid fa-house"></i></a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item" <?php hideIfUser(); ?>>
                        <a class="nav-link" href="?controller=index&action=login">Bejelentkezés <i class="fa-solid fa-arrow-right-to-bracket"></i></a>
                    </li>
                    <li class="nav-item me-md-5" <?php hideIfUser(); ?>>
                        <a class="nav-link" href="?controller=index&action=register">Regisztráció <i class="fa-solid fa-user-plus"></i></a>
                    </li>
                    <li class="nav-item" <?php hideIfGuest(); ?>>
                        <a class="nav-link" href="?controller=user&action=settings">Profiladatok <i class="fa-solid fa-user"></i></a>
                    </li>
                    <li class="nav-item me-md-5" <?php hideIfGuest(); ?>>
                        <a class="nav-link" href="?controller=index&action=logout">Kijelentkezés <i class="fa-solid fa-right-from-bracket"></i></a>
                    </li>   
                </ul>
            </div>
        </div>
    </nav>
</header>