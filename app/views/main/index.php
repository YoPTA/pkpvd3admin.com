<?php
$pagetitle = 'Главная';
$page_id = 'page_index';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <div class="uk-width-8-10" align="left">
        <div class="main_container">

            <?php
            if ($is_can_USER):
            ?>

                <div class="map">
                    <a href="/user/index?search=&page=1">
                        Пользователи ПК ПВД3
                        <br />
                        <span class="description">
                            Интерфейс позволяет: добавить, изменить или удалить пользователей в ПК ПВД3
                        </span>
                    </a>
                </div>

            <?php
            endif;// if ($is_can_USER):
            ?>

        </div>
    </div>



<?php include APP_VIEWS . 'layouts/footer.php'; ?>