<?php
$pagetitle = 'Пользователь';
$page_id = 'page_user';

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
                <legend class="app">Добавить</legend>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-1 caption">
                <span>Основные сведения</span>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['lastname']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['firstname']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['middlename']->render(); ?>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-2">
                <?= $html_element['job']->render(); ?>
            </div>

            <div class="uk-form-row uk-width-1-1 uk-width-small-1-1">
                <?= $html_element['organization']->render(); ?>
                <br /><br />
                <span id="organization_understudy"></span>
            </div>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-1 caption">
                <hr>
                <span>Права</span>
            </div>

            <?php
            $i = 1;
            foreach ($roles as $r_key => $r_value):
            ?>
                <div class="uk-form-row uk-width-1-1 uk-width-small-1-1">
                    <?= $html_element['roles_'.$i]->render(); ?>
                </div>
            <?php
                $i++;
            endforeach; // foreach ($roles as $r_key => $r_value):
            ?>
            <div class="uk-form-row uk-width-1-1 uk-width-small-1-1 caption">
                <hr>
                <span>Авторизация</span>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-password uk-width-1-1 uk-width-small-1-2">
                    <?= $html_element['login']->render(); ?>
                </div>
                <a class="uk-button" onclick="setLoginNow();" title="Сгенерировать логин из ФИО"><i class="uk-icon-hand-spock-o"></i></a>
            </div>

            <div class="uk-form-row">
                <div class="uk-form-password uk-width-1-1 uk-width-small-1-2">
                    <?= $html_element['password']->render(); ?>
                    <a href class="uk-form-password-toggle" id="pass_sh_h" data-uk-form-password style="margin-top: 3px;">
                        Показать
                    </a>
                </div>
                <a class="uk-button" onclick="setPassword('password', createRandomPasswordEx(6, true, true, true, false, ''));" title="Сгенерировать случайно"><i class="uk-icon-hand-spock-o"></i></a>
            </div>

            <div class="uk-form-row">
                <button class="uk-button" name="add">Добавить</button>
            </div>

        </form>
    </div>

    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script src="<?= APP_TEMPLATES ?>js/php.js" type="text/javascript"></script>
    <script src="<?= APP_TEMPLATES ?>js/app-utils.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#organization").chosen({no_results_text: "Пока нет организаций", search_contains: true});

        $('#organization_chosen').on('click', '', function () {
            if($("#organization_chosen li").hasClass('result-selected')){
                if ($(".chosen-single span").text() != '[выбрать]')
                {
                    $("#organization_understudy").html($(".chosen-single span").text());
                }
            }
        });

        <?php
        if (!$search['rs_admin']):
        ?>
        $("#organization_chosen span").css("color", "#999");
        $("#organization_understudy").html($("#organization option:selected").text());
        <?php
        endif;
        if (isset($errors['organization']) && $errors['organization'] != null):
        ?>
        document.getElementById('organization_chosen').className += ' ch_danger';
        <?php
        endif; //if (!$search['rs_admin']):
        ?>

        function setLoginNow()
        {
            var login =  GetRusToLatLogin(document.getElementById("lastname").value,
                document.getElementById("firstname").value, document.getElementById("middlename").value);
            document.getElementById("login").value = login;
        }
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>