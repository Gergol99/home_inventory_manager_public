<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container-fluid" id="list_page">
    <div class="mx-md-5">
        <div class="mt-3 mb-4">
            <div class="dropdown d-inline">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Háztartások
                </button>
                <ul class="dropdown-menu">
                    <?php
                    foreach ($households as $household) {
                        echo "<li>
                            <a class='dropdown-item' href='?controller=household&action=list&hid=$household->household_id'>
                            $household->household_name
                            </a>
                            </li>";
                    }
                    ?>
                </ul>
            </div>
            <?php
            $hideIfNewUser = '';
            $householdName = '';
            $householdId = '';
            if (isset($currentHousehold->name)) {
                $householdName = $currentHousehold->name;
                $householdId = $currentHousehold->id;
            } else {
                $hideIfNewUser = 'hidden';
            }
            ?>
            <div class="float-end">
                <a class="btn btn-outline-light" href="?controller=household&action=add" title="Új háztartás létrehozása">
                    <i class="fa-solid fa-house-medical fa-lg"></i>
                </a>
                <a class="btn btn-outline-light" href="?controller=household&action=invite&hid=<?php echo $householdId; ?>" title="Meghívás háztartásba" <?php echo $hideIfNewUser; ?>>
                    <i class="fa-solid fa-user-plus"></i>
                </a>
                <a class="btn btn-outline-light" href="?controller=users_in_household&action=settings&hid=<?php echo $householdId; ?>" title="Háztartás beállítások" <?php echo $hideIfNewUser; ?>>
                    <i class="fa-solid fa-gear"></i>
                </a>
            </div>
        </div>
        <?php
        echo "<div id='household_name_header'>";
        echo "<h2 $hideIfNewUser>";
        echo "$householdName";
        echo "<button class='btn btn-dark' id='household_name_btn' title='Szerkesztés'>";
        echo "<i class='fa-solid fa-pen-to-square fa-xl'></i>";
        echo "</button>";
        echo "</h2>";
        echo "<hr $hideIfNewUser>";
        echo "</div>";
        ?>
        <div id="household_name_form" hidden>
            <form action="" method="post">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg" name="household_name_input" id="household_name_input" maxlength="150" required>
                    <button type="submit" name="update_household_name" class="btn btn-success">OK</button>
                </div>
            </form>
            <hr>
        </div>
        <div class="row gy-5" <?php echo $hideIfNewUser; ?>>
            <div class="col-sm-6">
                <h3><i class="fa-solid fa-cart-shopping"></i> <span class="fw-bold">Bevásárlólista</span></h3>
                <table class="table table-striped" id="shopping_list_table">
                    <thead>
                        <tr>
                            <th>Megnevezés</th>
                            <th class="text-center">Szükséges mennyiség</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider align-middle">
                        <?php
                        foreach ($shoppingList as $shoppingItem) {
                            echo "<tr>";
                            echo "<td>$shoppingItem->name</td>";
                            $reqQuantity = $shoppingItem->req_quantity !== null ? "$shoppingItem->req_quantity $shoppingItem->measurement_name" : "$shoppingItem->min_quantity $shoppingItem->measurement_name";
                            echo "<td class='text-center'><span class='clickable shopping_list_req_quantity' data-inventory-id='$shoppingItem->id'>$reqQuantity</span></td>";
                            echo "<td class='dropstart text-end'>";
                            echo "<button class='btn btn-dark' data-bs-toggle='dropdown' aria-expanded='false'>";
                            echo "<i class='fa-solid fa-ellipsis-vertical'></i>";
                            echo "</button>";
                            echo "<ul class='dropdown-menu'>";
                            echo "<li><span class='dropdown-item shopping_list_complete' data-inventory-id='$shoppingItem->id'>";
                            echo "<i class='fa-solid fa-check shopping_list_complete'></i> Megvásárólva</span></li>";
                            echo "<li><hr class='dropdown-divider'></li>";
                            echo "<li><span class='dropdown-item shopping_list_remove'
                            data-inventory-id='$shoppingItem->id'
                            data-important='$shoppingItem->important'><i class='fa-solid fa-xmark shopping_list_remove'></i> Levétel a  bevásárlólistáról</span></li>";
                            echo "</ul>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">
                                <div class="d-flex justify-content-center gap-3">
                                    <a class="btn btn-dark" href="index.php?controller=inventory&action=add_via_shopping_list&hid=<?php echo $householdId; ?>">
                                        <i class="fa-regular fa-square-plus fa-xl"></i>
                                    </a>
                                    <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-warehouse"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php
                                        foreach ($inventories as $inventory) {
                                            echo "<li><span class='dropdown-item to_shopping_list_from_inventory' data-inventory-id='$inventory->id' 
                                            data-measurement-name='$inventory->measurement_name'>$inventory->name</span></li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6">
                <h3><i class="fa-solid fa-boxes-stacked"></i> <span class="fw-bold">Készlet</span></h3>
                <table class="table table-striped" id="inventory_table">
                    <thead>
                        <tr>
                            <th>Megnevezés</th>
                            <th class="text-center">Mennyiség</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider align-middle">
                        <?php
                        if (isset($inventories)) {
                            foreach ($inventories as $inventory) {
                                echo "<tr data-inventory-id='$inventory->id'>";
                                echo "<td><span class='clickable item_name_field'>$inventory->name</span></td>";
                                echo "<td class='text-center' data-measurement-id='$inventory->measurement_id'>";
                                echo "<span class='clickable item_measurement_field'>$inventory->quantity $inventory->measurement_name</span>";
                                echo "</td>";
                                echo "<td class='dropstart text-end'>";
                                echo "<button class='btn btn-dark' data-bs-toggle='dropdown' aria-expanded='false'>";
                                echo "<i class='fa-solid fa-ellipsis-vertical'></i>";
                                echo "</button>";
                                echo "<ul class='dropdown-menu'>";
                                echo "<li><span class='dropdown-item item_to_shopping_list_field' data-inventory-id='$inventory->id'
                                data-measurement-name='$inventory->measurement_name'><i class='fa-solid fa-cart-plus item_to_shopping_list_field'></i> Bevásárlólistára</span></li>";
                                echo "<li>";
                                echo "<span class='dropdown-item item_important_field' data-min-quantity='$inventory->min_quantity'
                                data-measurement-name='$inventory->measurement_name'
                                data-inventory-id='$inventory->id'>";
                                if ($inventory->important === 1) {
                                    echo "<i class='far fa-square-check fa-lg item_important_field'></i>";
                                } else {
                                    echo "<i class='far fa-square fa-lg item_important_field'></i>";
                                }
                                echo " Alapvető</span></li>";
                                echo "<li><hr class='dropdown-divider'></li>";
                                echo "<li><span class='dropdown-item item_delete_field'
                                data-inventory-id='$inventory->id'>";
                                echo "<i class='fa-solid fa-trash item_delete_field'></i> Törlés</span></li>";
                                echo "</ul>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">
                                <a class="btn btn-dark" href="index.php?controller=inventory&action=add&hid=<?php echo $householdId; ?>">
                                    <i class="fa-regular fa-square-plus fa-xl"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<dialog id="item_name_dialog">
    <form method="post">
        <input type="text" name="item_name_id" id="item_name_id" hidden>
        <input type="text" class="form-control" name="item_name" id="item_name" required>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="submit" name="update_item_name" class="btn btn-success w-100">
                Mentés <i class="fa-solid fa-floppy-disk"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<dialog id="item_quantity_dialog">
    <form method="post">
        <input type="text" name="item_quantity_id" id="item_quantity_id" hidden>
        <select class="form-select" name="measurement_name" id="measurement_name">
            <?php
            foreach ($measurements as $measurement) {
                echo "<option value='$measurement->id'>$measurement->name</option>";
            }
            ?>
        </select>
        <div class="input-group mt-3">
            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()"><i class="fas fa-minus"></i></button>
            <input type="number" class="form-control text-center" name="item_quantity" id="item_quantity" min="0" max="1000000" placeholder="Mennyiség" required>
            <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()"><i class="fas fa-plus"></i></button>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="submit" name="update_item_quantity" class="btn btn-success w-100">
                Mentés <i class="fa-solid fa-floppy-disk"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<dialog id="item_important_dialog">
    <form method="post">
        <input type="text" name="item_important_id" id="item_important_id" hidden>
        <label for="item_min_quantity">Minimum mennyiség</label>
        <div class="input-group">
            <input type="number" class="form-control" name="item_min_quantity" id="item_min_quantity" min="0" max="1000000" placeholder="Min mennyiség" required>
            <span class="input-group-text" id="item_important_measurement_name"></span>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="submit" name="update_item_important" class="btn btn-success w-100">
                Mentés <i class="fa-solid fa-floppy-disk"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<dialog id="item_delete_dialog">
    <form method="post">
        <h3 class="display-5 text-center">Biztos törli?</h3>
        <input type="text" name="item_delete_id" id="item_delete_id" hidden>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <button type="submit" name="delete_item" class="btn btn-danger w-100">
                Törlés <i class="fa-solid fa-trash"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<dialog id="item_shopping_list_dialog">
    <form method="post">
        <input type="text" name="item_shopping_list_id" id="item_shopping_list_id" hidden>
        <div class="input-group">
            <input type="number" class="form-control" name="item_req_quantity" id="item_req_quantity" min="0" max="1000000" placeholder="Szükséges mennyiség" required>
            <span class="input-group-text" id="item_shopping_list_measurement_name"></span>
        </div>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <button type="submit" name="add_to_shopping_list" class="btn btn-success w-100">
                Ok <i class="fa-solid fa-check"></i>
            </button>
            <button class="btn btn-secondary w-100" onclick="cancelShoppingListDialog()" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<dialog id="shopping_list_remove_warning_dialog">
    <form method="post">
        <h3 class="display-5 text-center">Biztos leveszi az elemet a listáról?</h3>
        <p>Az elem 'alapvető' tulajdonsága is kikapcsolódik!</p>
        <input type="text" name="shopping_list_id" id="shopping_list_id" hidden>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <button type="submit" name="remove_important_from_shopping_list" class="btn btn-danger w-100">
                Levétel <i class="fa-solid fa-trash"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<dialog id="shopping_list_req_quantity_dialog">
    <form method="post">
        <input type="text" name="shopping_list_req_quantity_id" id="shopping_list_req_quantity_id" hidden>
        <div class="input-group mt-3">
            <button type="button" class="btn btn-outline-secondary" onclick="decreaseReqQuantity()"><i class="fas fa-minus"></i></button>
            <input type="number" class="form-control text-center" name="shopping_list_req_quantity" id="shopping_list_req_quantity" min="0" max="1000000" placeholder="Szükséges mennyiség" required>
            <button type="button" class="btn btn-outline-secondary" onclick="increaseReqQuantity()"><i class="fas fa-plus"></i></button>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="submit" name="update_shopping_list_req_quantity" class="btn btn-success w-100">
                Mentés <i class="fa-solid fa-floppy-disk"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<script>
    const householdNameEditBtn = document.getElementById("household_name_btn");
    const householdNameHeader = document.getElementById("household_name_header");
    const householdNameForm = document.getElementById("household_name_form");
    const listPage = document.getElementById("list_page");
    listPage.addEventListener("click", (e) => {
        if (householdNameEditBtn.contains(e.target)) {
            householdNameHeader.hidden = true;
            householdNameForm.hidden = false;
            document.getElementById("household_name_input").value = householdNameHeader.firstChild.textContent;
        } else if (!householdNameForm.contains(e.target)) {
            householdNameHeader.hidden = false;
            householdNameForm.hidden = true;
        }
    });
</script>

<script>
    const inventoryTable = document.getElementById("inventory_table");

    const itemNameDialog = document.getElementById("item_name_dialog");
    const itemName = document.getElementById("item_name");
    const itemNameId = document.getElementById("item_name_id");

    const itemQuantityDialog = document.getElementById("item_quantity_dialog");
    const itemQuantity = document.getElementById("item_quantity");
    const measurementName = document.getElementById("measurement_name");
    const itemQuantityId = document.getElementById("item_quantity_id");

    const itemImportantDialog = document.getElementById("item_important_dialog");
    const itemMinQuantity = document.getElementById("item_min_quantity");
    const itemImportantId = document.getElementById("item_important_id");
    const itemImportantMeasurementName = document.getElementById("item_important_measurement_name");

    const itemDeleteDialog = document.getElementById("item_delete_dialog");
    const itemDeleteId = document.getElementById("item_delete_id");

    const itemShoppingListDialog = document.getElementById("item_shopping_list_dialog");
    const itemShoppingListId = document.getElementById("item_shopping_list_id");
    const itemReqQuantity = document.getElementById("item_req_quantity");
    const itemShoppingListMeasurementName = document.getElementById("item_shopping_list_measurement_name");


    const shoppingListTable = document.getElementById("shopping_list_table");

    const shoppingListRemoveWarningDialog = document.getElementById("shopping_list_remove_warning_dialog");
    const shoppingListId = document.getElementById("shopping_list_id");

    const shoppingListReqQuantityDialog = document.getElementById("shopping_list_req_quantity_dialog");
    const shoppingListReqQuantityId = document.getElementById("shopping_list_req_quantity_id");
    const shoppingListReqQuantity = document.getElementById("shopping_list_req_quantity");

    inventoryTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("item_name_field")) {
            itemName.value = e.target.textContent;
            itemNameId.value = e.target.parentNode.parentNode.dataset.inventoryId;
            itemNameDialog.showModal();
        } else if (e.target.classList.contains("item_measurement_field")) {
            let temp = e.target.textContent.split(' ');
            itemQuantity.value = temp[0];
            itemQuantityId.value = e.target.parentNode.parentNode.dataset.inventoryId;
            measurementName.value = e.target.parentNode.dataset.measurementId;
            itemQuantityDialog.showModal();
        } else if (e.target.classList.contains("item_important_field")) {
            let checkbox = null;
            if (e.target.tagName === "SPAN") {
                checkbox = e.target.firstChild;
            } else {
                checkbox = e.target;
            }

            let inventoryId = checkbox.parentNode.dataset.inventoryId;
            if (checkbox.classList.contains("fa-square-check")) {
                let formData = new FormData();
                formData.append('inventory_id', inventoryId);
                formData.append('disable_important', 'submit');

                var form = document.createElement('form');
                form.method = 'post';
                form.action = window.location.href;
                for (var pair of formData.entries()) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = pair[0];
                    input.value = pair[1];
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            } else if (checkbox.classList.contains("fa-square")) {
                itemImportantId.value = inventoryId;
                let minQuantity = checkbox.parentNode.dataset.minQuantity;
                itemMinQuantity.value = minQuantity !== '' ? minQuantity : 1;
                itemImportantMeasurementName.innerHTML = checkbox.parentNode.dataset.measurementName;
                itemImportantDialog.showModal();
            }
        } else if (e.target.classList.contains("item_delete_field")) {
            let deleteField = null;
            if (e.target.tagName === "SPAN") {
                deleteField = e.target.firstChild;
            } else {
                deleteField = e.target;
            }

            itemDeleteId.value = deleteField.parentNode.dataset.inventoryId;
            itemDeleteDialog.showModal();
        } else if (e.target.classList.contains("item_to_shopping_list_field")) {
            itemReqQuantity.value = '';
            let checkbox = null;
            if (e.target.tagName === "SPAN") {
                checkbox = e.target.firstChild;
            } else {
                checkbox = e.target;
            }
            itemShoppingListId.value = checkbox.parentNode.dataset.inventoryId;
            itemShoppingListMeasurementName.innerHTML = checkbox.parentNode.dataset.measurementName;
            itemShoppingListDialog.showModal();
        }
    });

    shoppingListTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("shopping_list_remove")) {
            let button = null;
            if (e.target.tagName === "SPAN") {
                button = e.target.firstChild;
            } else {
                button = e.target;
            }

            let inventoryId = button.parentNode.dataset.inventoryId;

            if (button.parentNode.dataset.important == 0) {
                let formData = new FormData();
                formData.append('inventory_id', inventoryId);
                formData.append('remove_from_shopping_list', 'submit');

                var form = document.createElement('form');
                form.method = 'post';
                form.action = window.location.href;
                for (var pair of formData.entries()) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = pair[0];
                    input.value = pair[1];
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            } else {
                shoppingListId.value = inventoryId;
                shoppingListRemoveWarningDialog.showModal();
            }
        } else if (e.target.classList.contains("shopping_list_complete")) {
            let button = null;
            if (e.target.tagName === "SPAN") {
                button = e.target.firstChild;
            } else {
                button = e.target;
            }

            let inventoryId = button.parentNode.dataset.inventoryId;
            let formData = new FormData();
            formData.append('inventory_id', inventoryId);
            formData.append('complete_shopping_list_item', 'submit');

            var form = document.createElement('form');
            form.method = 'post';
            form.action = window.location.href;
            for (var pair of formData.entries()) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = pair[0];
                input.value = pair[1];
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        } else if (e.target.classList.contains('shopping_list_req_quantity')) {
            shoppingListReqQuantityId.value = e.target.dataset.inventoryId;
            let temp = e.target.textContent.split(' ');
            shoppingListReqQuantity.value = temp[0];
            shoppingListReqQuantityDialog.showModal();
        } else if (e.target.classList.contains('to_shopping_list_from_inventory')) {
            itemShoppingListId.value = e.target.dataset.inventoryId;
            itemReqQuantity.value = '';
            itemShoppingListMeasurementName.innerHTML = e.target.dataset.measurementName;
            itemShoppingListDialog.showModal();
        }
    });

    function increaseQuantity() {
        itemQuantity.value++;
    }

    function decreaseQuantity() {
        if (itemQuantity.value > 0) {
            itemQuantity.value--;
        }
    }

    function cancelShoppingListDialog() {
        itemReqQuantity.value = 0;
    }

    function increaseReqQuantity() {
        shoppingListReqQuantity.value++;
    }

    function decreaseReqQuantity() {
        if (shoppingListReqQuantity.value > 0) {
            shoppingListReqQuantity.value--;
        }
    }
</script>

<?php
require_once 'App/View/footer.php';
?>