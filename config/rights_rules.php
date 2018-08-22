<?php

$user_id = User::checkLogged();
if (!$user_id)
{
    header('Location: /main/login');
}

/*$user_rights = User::getUserRights($user_id);
$menu_panel = new Menu_Panel();*/

