<?php
$pagetitle = 'Пользователь';
$page_id = 'page_moderator';

//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>
    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/user/index?<?= $url_param ?>">&larr; Вернуться назад</a>
    <script src="<?= APP_TEMPLATES ?>js/form-password.min.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/random.js" type="text/javascript"></script>
    <script type="text/javascript">
        function setPassword(field_id, password)
        {
            $("#"+field_id).val(password);
            $("#"+field_id).prop('type', 'text');
            $("#pass_sh_h").html('Скрыть');
        }
    </script>

    <div data-uk-grid class="uk-width-1-2 uk-margin-large-top uk-align-center">
        <?php
        if (is_array($errors) && count($errors) > 0):
            foreach ($errors as $error):
                echo App_Message::getMessage($error, MESSAGE_TYPE_ERROR);
            endforeach; // foreach ($errors as $error):
        endif; //if (is_array($errors) && count($errors) > 0):
        ?>

        <form method="POST" class="uk-form">
            <div class="uk-form-row">
                <legend class="app">Редактировать</legend>
            </div>
            <?php if ($this_user['surName'] != null): ?>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= trim($this_user['surName']. ' ' .$this_user['firstName'].' '.$this_user['patronymic']) ?>
            </div>
            <?php endif; //if ($this_user['surName'] != null): ?>
            <div class="uk-form-row">
                <div class="uk-form-password uk-width-1-1 uk-width-small-1-2">
                    <?= $html_element['password']->render(); ?>
                    <a href class="uk-form-password-toggle" id="pass_sh_h" data-uk-form-password style="margin-top: 3px;">
                        Показать
                    </a>
                </div>
                <a class="uk-button" onclick="setPassword('password', createRandomPasswordEx(6, true, true, true, false, ''));" title="Сгенерировать случайно"><i class="uk-icon-hand-spock-o"></i></a>
            </div>
            <?php if ($user_password_change_flag): ?>
                <div class="uk-form-row uk-width-1-1">
                    <button class="uk-button" name="edit">Редактировать</button>
                </div>
            <?php endif; //if ($user_password_change_flag): ?>

        </form>
    </div>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>