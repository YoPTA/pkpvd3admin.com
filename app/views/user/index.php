<?php
$pagetitle = 'Пользователи';
$page_id = 'page_user';


//Подключаем шапку
include APP_VIEWS . 'layouts/header.php';
?>

    <h1><?= $pagetitle ?></h1>
    <a class="back" href="/main/index">&larr; Вернуться назад</a>

    <div class="uk-width-8-10" align="left">

        <form method="GET" class="uk-form simple" id="parameters">
            <table class="uk-width-1-1 search_param">

                <tr>
                    <td class="uk-width-1-4 indent-right">
                        <?= $html_element['name']->render() ?>
                    </td>

                    <td class="uk-width-1-4 indent-right">
                        <?= $html_element['job']->render() ?>
                    </td>

                    <td class="uk-width-1-4 indent-right">
                        <?= $html_element['status']->render() ?>
                    </td>



                    <td class="uk-width-1-4">
                        <?php
                        if (!$search['rs_admin']):
                        ?>
                        <button class="uk-button indent-right">Поиск</button>
                        <button class="uk-button indent-right" name="reset" title="Сбросить форму">Сброс</button>

                        <a href="/user/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
                            <?php
                        endif; // if (!$search['rs_admin']):
                        ?>
                    </td>

                </tr>
            </table>
            <?php
            if ($search['rs_admin']):
            ?>
            <table class="uk-width-1-1 search_param">
				<tr>
					<td class="uk-width-3-4 indent-right">
						<?= $html_element['organization']->render() ?>
					</td>

                    <td class="uk-width-1-4">
                        <button class="uk-button indent-right">Поиск</button>
                        <button class="uk-button indent-right" name="reset" title="Сбросить форму">Сброс</button>
                        <a href="/user/add?<?= $url_param ?>" class="uk-button fr" title="Добавить">Добавить</a>
                    </td>
				</tr>
                    
            </table>
            <?php
            endif; //if ($search['rs_admin']):
            $url_address = '/user/index?'.$url_param;
            ?>
        </form>

        <table class="uk-width-1-1 view" id="table">
            <thead>
                <tr>
                    <th class="uk-width-1-10" title="Порядковый номер">№</th>
                    <th class="uk-width-3-10 sort" title="ФИО" onclick="setUrl('<?= $url_address ?>', '<?= ($sort == 0)? 1 : 0; ?>')">
                        ФИО
                        <?php
                        if ($sort == 0) {
                            echo ' <i class="uk-icon-caret-up mini"></i>';
                        }
                        if ($sort == 1) {
                            echo ' <i class="uk-icon-caret-down mini"></i>';
                        }
                        ?>
                        <br />
                        <span class="description_light">Логин</span>
                    </th>
                    <th class="uk-width-1-10 sort" title="Должность" onclick="setUrl('<?= $url_address ?>', '<?= ($sort == 2)? 3 : 2; ?>')">
                        Должность
                        <?php
                        if ($sort == 2) {
                            echo ' <i class="uk-icon-caret-up mini"></i>';
                        }
                        if ($sort == 3) {
                            echo ' <i class="uk-icon-caret-down mini"></i>';
                        }
                        ?>
                    </th>
                    <th class="uk-width-4-10 sort" title="Организация" onclick="setUrl('<?= $url_address ?>', '<?= ($sort == 4)? 5 : 4; ?>')">
                        Организация
                        <?php
                        if ($sort == 4) {
                            echo ' <i class="uk-icon-caret-up mini"></i>';
                        }
                        if ($sort == 5) {
                            echo ' <i class="uk-icon-caret-down mini"></i>';
                        }
                        ?>
                        <br />
                        <span class="description_light">Код организации</span>
                    </th>
                    <th class="uk-width-1-10">Действие</th>
                </tr>
            </thead>

            <?php
            $i=0;
            if (is_array($users) && count($users) > 0):
                foreach ($users as $user):
                    $i++;
                    $index_number++;
                ?>
                <tr class="<?php  echo ($user['status'] == STATUS_ARCHIVE)? 'archive':'srow';  ?>">
                    <td><?= $index_number ?></td>
                    <td>
                        <?= trim($user['surName'] . ' ' . $user['firstName'] . ' ' . $user['patronymic']) ?>
                        <br /><span class="description_light"><?= $user['login'] ?></span>
                    </td>
                    <td><?= $user['job'] ?></td>
                    <td>
                        <?= $user['orgName'] ?>
                        <br /><span class="description_light"><?= $user['orgCode'] ?></span>
                    </td>
                    <td>
                        <a href="/user/edit?<?= $url_param . '&uid='.$user['_id'] ?>" class="action" title="Редактировать"><span class="uk-icon-pencil"></span></a>
                        <?php
                        if ($user['status'] != STATUS_ARCHIVE):
                        ?>
                            <a href="/user/delete?<?= $url_param . '&uid='.$user['_id'] ?>" class="action" title="Переместить в архив"><span class="uk-icon-trash"></span></a>
                        <?php
                        else: // if ($user['status'] != STATUS_ARCHIVE):
                        ?>
                            <a href="/user/restore?<?= $url_param . '&uid='.$user['_id'] ?>" class="action" title="Восстановить учетную запись"><span class="uk-icon-user"></span></a>
                        <?php
                        endif; // if ($user['status'] != STATUS_ARCHIVE):
                        ?>
                        <a href="/user/password?<?= $url_param . '&uid='.$user['_id'] ?>" class="action" title="Изменить пароль"><span class="uk-icon-lock" style="padding-left: 5px; padding-right: 5px;"></span></a>
                    </td>
                </tr>
                <?php
                endforeach; // foreach ($users as $user):
            endif; // if (is_array($users) && count($users) > 0):
            ?>
            <?php
            include APP_VIEWS . 'layouts/record_count.php';
            echo recordCount($total, $i);
            ?>
        </table>

        <?= $pagination->get() ?>


    </div>
    <script src="<?= APP_TEMPLATES ?>css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#organization").chosen({no_results_text: "Пока нет организаций", search_contains: true});
        $("#status").chosen({no_results_text: "Пока нет статусов", search_contains: true});

        function setUrl(url, value)
        {
            return document.location.href = url+"&sort="+value;
        }
    </script>

<?php include APP_VIEWS . 'layouts/footer.php'; ?>