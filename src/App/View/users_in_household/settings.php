<?php
require_once 'App/View/header.php';
require_once 'App/View/menu.php';
?>

<div class="container">
    <h2 class="mt-5">
        <?php 
        echo $household->name;
        if ($_SESSION['user_id'] == $household->admin_user_id) {
            echo "<button class='float-end btn btn-dark' onclick='showDeleteHouseholdDialog()'><i class='fa-solid fa-trash fa-lg'></i></button>";
        }
        ?>
    </h2>
    <hr class="mb-5">
    <table class="table table-striped" id="users_table">
        <thead>
            <tr>
                <th colspan="2" class="text-center">Felhasználók</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>$user->username</td>";
                echo "<td class='text-end'>";
                if ($user->user_id == $household->admin_user_id) {
                    echo "admin";
                } else if ($_SESSION['user_id'] == $household->admin_user_id) {
                    echo "<span class='clickable remove_btn' data-target-user-id='$user->user_id'><i class='fa-solid fa-ban remove_btn'></i></span>";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<dialog id="delete_household_dialog">
    <form action="?controller=household&action=delete&hid=<?php echo $household->id; ?>" method="post">
        <h1 class="text-center">Biztos törli a háztartást?</h1>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <button type="submit" name="delete_household" class="btn btn-danger w-100">
                Törlés <i class="fa-solid fa-trash"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<dialog id="remove_user_dialog">
    <form method="post">
        <h1 class="text-center">Biztos eltávolítja a felhasználót?</h1>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <input type="text" name="target_user_id" id="target_user_id" hidden>
            <button type="submit" name="remove_user" class="btn btn-danger w-100">
                Eltávolítás <i class="fa-solid fa-trash"></i>
            </button>
            <button class="btn btn-secondary w-100" formmethod="dialog">
                Mégse <i class="fa-solid fa-arrow-left"></i>
            </button>
        </div>
    </form>
</dialog>

<script>
    const usersTable = document.getElementById('users_table');
    const targetUserId = document.getElementById('target_user_id');
    const removeUserDialog = document.getElementById('remove_user_dialog');

    usersTable.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove_btn')) {
            let btn = null;
            if (e.target.tagName === "SPAN") {
                btn = e.target.firstChild;
            } else {
                btn = e.target;
            }

            targetUserId.value = btn.parentNode.dataset.targetUserId;
            removeUserDialog.showModal();
        }
    });


    function showDeleteHouseholdDialog() {
        const deleteHouseholdDialog = document.getElementById('delete_household_dialog');
        deleteHouseholdDialog.showModal();
    }
</script>

<?php
require_once 'App/View/footer.php';
?>