<?php
$rand_param = rand(10000, 33333);
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= DEFAULT_ENCODING_UPPERCASE ?>" />
    <link rel="shortcut icon" href="/favicon.ico?<?= $rand_param ?>" type="image/x-icon">
    <link href="<?= APP_TEMPLATES ?>css/uikit.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/chosen/chosen.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/main.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/pagination.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/nav.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/form-password.min.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/font-awesome.min.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/app_messages.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/form-file.css?<?= $rand_param ?>" rel="stylesheet">
    <link href="<?= APP_TEMPLATES ?>css/upload-img.css?<?= $rand_param ?>" rel="stylesheet">

    <script src="<?= APP_TEMPLATES ?>js/nav.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/offcanvas.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/jquery-3.2.1.min.js"></script>
    <script src="<?= APP_TEMPLATES ?>js/uikit.js"></script>

    <title><?= $pagetitle;?></title>
</head>
<body>
<table class="body" cellpadding="0" cellspacing="0" align="center">
    <tr id="header">
        <td>
            <nav class="uk-navbar">
                <div class="uk-navbar-content" style="padding-left: 0;">
                    <?/*= parent::getMenuPanel()*/ ?>
                </div>

                <?php if (USER_ID !== false): ?>
                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav">
                        <li>
                            <a href="/main/logout">
                                <i class="uk-icon-sign-out"></i>Выход
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; // if (USER_ID !== false): ?>
            </nav>
        </td>
    </tr>
    <tr id="content">
        <td style="vertical-align: text-top">
            <div data-uk-grid class="uk-grid uk-grid-collapse">
                <div class="uk-width-1-1 uk-margin-large-bottom" align="center">