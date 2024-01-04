<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container">
    <h1 class="text-center mt-5 mb-3 display-3">Új termék hozzáadása</h1>
    <div class="row justify-content-center">
        <form action="" class="col-md-6 col-xl-5" method="post">
            <div class="mt-3">
                <input type="text" class="form-control" name="name" id="name" placeholder="Megnevezés" required>
            </div>
            <div class="input-group mt-3">
                <input type="number" class="form-control" name="quantity" id="quantity" min="0" max="1000000" placeholder="Mennyiség" required>
                <select class="form-select" name="measurement_name" id="measurement_name">
                    <?php
                    foreach ($measurements as $measurement) {
                        echo "<option value='$measurement->id'>$measurement->name</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mt-3">
                <select class="form-select" name="item_category" id="item_category">
                    <?php
                    foreach ($itemCategories as $itemCategory) {
                        echo "<option value='$itemCategory->id'>$itemCategory->name</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input-group mt-3">
                <div class="input-group-text">
                    <label class="me-1" for="important">Alapvető</label>
                    <input type="checkbox" class="form-check-input" name="important" id="important">
                </div>
                <input type="number" class="form-control" name="min_quantity" id="min_quantity" min="0" max="1000000" disabled placeholder="Min mennyiség" required>
            </div>
            <p>
                Az "alapvető"-nek jelölt termékek automatikusan felkerülnek a bevásárlólistára, ha kevesebb van belőlük az elvárt minimális értéknél.
            </p>
            <div class="d-flex gap-2 justify-content-center mt-3 mb-5">
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

<script>
    const important = document.getElementById("important");
    const minQuantity = document.getElementById("min_quantity");
    important.addEventListener("change", () => {
        console.log(important.checked);
        if (important.checked) {
            minQuantity.disabled = false;
        } else {
            minQuantity.disabled = true;
        }
    });
</script>

<?php
require_once 'App/View/footer.php';
?>