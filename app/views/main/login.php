<?php
$pagetitle = 'Авторизация';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <div data-uk-grid class=" uk-width-1-2 uk-margin-large-top uk-margin-large-bottom uk-align-center">
        <?php
        if (is_array($errors) && count($errors) > 0):
            foreach ($errors as $error):
                echo App_Message::getMessage($error, MESSAGE_TYPE_ERROR);
            endforeach; // foreach ($errors as $error):
        endif; //if (is_array($errors) && count($errors) > 0):
        ?>

        <form method="POST" class="uk-form">
            <div class="uk-form-row">
                <legend>Авторизация</legend>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-icon uk-width-1-1 uk-width-small-1-2">
                    <i class="uk-icon-user"></i>
                    <?= $html_element['login']->render(); ?>
                </div>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-icon uk-width-1-1 uk-width-small-1-2">
                    <i class="uk-icon-lock"></i>
                    <?= $html_element['password']->render(); ?>
                </div>
            </div>
            <div class="uk-form-row">
                <button class="uk-button" name="enter">Вход</button>
            </div>
        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>