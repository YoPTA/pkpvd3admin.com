<?php

class MainController extends BaseController
{
    public function actionIndex()
    {
        $is_can = false;
        if (!USER_ID)
        {
            header('Location: /main/login');
        }
        else
        {
            $is_can = true;
        }

        $user_right = parent::getUserRight();
        $is_can_USER = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r == RS_ADMIN)
            {
                $is_can_USER = true;
            }
            if ($u_r == RS_MAN)
            {
                $is_can_USER = true;
            }
            if ($is_can && $is_can_USER)
            {
                break;
            }
        }

        _gt_view:
        include_once APP_VIEWS.'main/index.php';
    }

    public function actionLogin()
    {
        $errors = false;
        $user = new User();
        $html_element['login'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['login']->setConfig('type', 'text');
        $html_element['login']->setConfig('class', 'uk-width-1-1');
        $html_element['login']->setName('login');
        $html_element['login']->setId('login');
        $html_element['login']->setConfig('placeholder', 'Логин');
        $html_element['login']->setValueFromRequest();

        $html_element['password'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['password']->setConfig('type', 'password');
        $html_element['password']->setConfig('class', 'uk-width-1-1');
        $html_element['password']->setName('password');
        $html_element['password']->setId('password');
        $html_element['password']->setConfig('placeholder', 'Пароль');
        $html_element['password']->setValueFromRequest();

        if (isset($_POST['enter']))
        {
            $user_data = [];

            if ($errors === false)
            {
                $user_data['login'] = $html_element['login']->getValue();
                $user_data['password'] = $user->createPVDFormatPassword($html_element['password']->getValue());
                $u_id = User::checkUserData($user_data);

                if ($u_id !== false && $u_id != null)
                {
                    User::auth($u_id);
                    // Раскомментировать код, если понадобится создавать папки
                    /*$app_directory = new App_Directory();
                    $dir_path = '/temp/users';
                    $temp_user_dir = ROOT.$dir_path.'/'.$u_id;
                    // Удаляем директорию, если она есть
                    $app_directory->removeDirectory($temp_user_dir);
                    if (!mkdir($temp_user_dir, 0777, true))
                    {
                        $errors['not_dir'] = 'Не удалось создать временную директорию пользователя';
                    }*/
                    header('Location: /main/index');
                }
                else
                {
                    $errors['not_user'] = 'Данные для входа заданы не верно.';
                }
            }
        }

        _gt_view:
        include_once APP_VIEWS.'main/login.php';
    }

    public function actionLogout()
    {
        User::logout();
    }

    public function actionError()
    {
        include_once APP_VIEWS.'main/error.php';
    }
}