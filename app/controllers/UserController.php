<?php

class UserController extends BaseController
{
    public function actionIndex()
    {
        $user_right = parent::getUserRight();
        $is_can = false;
        $index_number = 1;
        $page = 1;
        $total = 0;
        $user_organization = null;
        $url_param = "";
        $search = array();
        $search['rs_man'] = false;
        $search['rs_admin'] = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r == RS_ADMIN)
            {
                $search['rs_admin'] = true;
                $is_can = true;
            }
            if ($u_r == RS_MAN)
            {
                $search['rs_man'] = true;
                $user_organization = User::getMiaUserOrganization(USER_ID);
                $is_can = true;
            }
            if ($search['rs_admin'] && $search['rs_man'])
            {
                break;
            }
        }

        if (isset($_GET['page']))
        {
            $page = htmlspecialchars($_GET['page']);
        }

        if (isset($_REQUEST['reset']))
        {
            unset($_REQUEST);
        }

        if ($page < 1)
        {
            $page = 1;
        }

        $html_element['name'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['name']->setName('name');
        $html_element['name']->setId('name');
        $html_element['name']->setCaption('ФИО или логин');
        $html_element['name']->setConfig('type', 'text');
        $html_element['name']->setConfig('class', 'uk-width-1-1');
        $html_element['name']->setConfig('autocomplete', 'off');
        $html_element['name']->setConfig('placeholder', 'ФИО или логин');
        $html_element['name']->setValueFromRequest();
        $html_element['name']->setValue(trim($html_element['name']->getValue()));

        $html_element['job'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['job']->setName('job');
        $html_element['job']->setId('job');
        $html_element['job']->setCaption('Должность');
        $html_element['job']->setConfig('type', 'text');
        $html_element['job']->setConfig('class', 'uk-width-1-1');
        $html_element['job']->setConfig('autocomplete', 'off');
        $html_element['job']->setConfig('placeholder', 'Должность');
        $html_element['job']->setValueFromRequest();
        $html_element['job']->setValue(trim($html_element['job']->getValue()));

        $organiztaions = Organization::getAdmPvdOrganizationsByStatus(STATUS_ACTUAL);

        $option_organization = array();

        $i = 0;
        $option_organization[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_organization[$i]->setText('[все]');

        $temp_organizations = array();

        $i = 1;
        foreach ($organiztaions as $value)
        {
            if ($value['status'] == STATUS_ACTUAL)
            {
                $option_organization[$i] = new \HTMLElement\HTMLSelectOptionElement();
                $option_organization[$i]->setValue($value['code']);
                $option_organization[$i]->setText($value['name'].' ['.$value['code'].']');
                $temp_organizations[$value['code']] = $value['name'];
            }
            $i++;
        }

        $html_element['organization'] = new \HTMLElement\HTMLSelectElement($option_organization);
        $html_element['organization']->setCaption('Организация [код организации]');
        $html_element['organization']->setName('organization');
        $html_element['organization']->setId('organization');
        $html_element['organization']->setConfig('onchange', 'this.form.submit();');
        $html_element['organization']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['organization']->setConfig('class', 'uk-width-1-1 chosen-select');
        $html_element['organization']->setValueFromRequest();

        $option_status = array();

        $i = 0;
        $option_status[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_status[$i]->setText('[все]');

        $i = 1;
        $option_status[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_status[$i]->setValue(STATUS_ACTUAL);
        $option_status[$i]->setText('Актуальные');

        $i = 2;
        $option_status[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_status[$i]->setValue(STATUS_ARCHIVE);
        $option_status[$i]->setText('Архивные');

        $html_element['status'] = new \HTMLElement\HTMLSelectElement($option_status);
        $html_element['status']->setCaption('Статус');
        $html_element['status']->setName('status');
        $html_element['status']->setId('status');
        $html_element['status']->setConfig('onchange', 'this.form.submit();');
        $html_element['status']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['status']->setConfig('class', 'uk-width-1-1 chosen-select');
        $html_element['status']->setValueFromRequest();

        $search['name'] = $html_element['name']->getValue();
        $search['job'] = $html_element['job']->getValue();
        $search['status'] = $html_element['status']->getValue();

        $search['sort'] = 0;

        $sort_comparer = array();
        $sort_comparer[0] = array('key'=> 'name', 'alt' => 1, 'value' => 1);
        $sort_comparer[1] = array('key'=> 'name', 'alt' => 0, 'value' => -1);
        $sort_comparer[2] = array('key'=> 'job', 'alt' => 3, 'value' => 1);
        $sort_comparer[3] = array('key'=> 'job', 'alt' => 2, 'value' => -1);
        $sort_comparer[4] = array('key'=> 'org', 'alt' => 5, 'value' => 1);
        $sort_comparer[5] = array('key'=> 'org', 'alt' => 4, 'value' => -1);

        $sort = 0;
        if (isset($_REQUEST['sort']))
        {
            $sort = htmlspecialchars($_REQUEST['sort']);
            if (!array_key_exists($sort, $sort_comparer))
            {
                $sort = 0;
            }
        }

        $search['sort'] = $sort_comparer[$sort]['key'];
        $search['desc'] = $sort_comparer[$sort]['value'];

        if ($search['rs_admin'])
        {
            $search['organization'] = $html_element['organization']->getValue();
        }
        else
        {
            $search['organization'] = $user_organization['orgCode'];
        }

        $users = User::getMiaUsers($search, $page, $total);

        /*$user = new User();
        echo $user->createPVDFormatPassword('123456'); */

        /*$file_name = 'office4.csv'; // Южная
        $file = ROOT.'/temp/'.$file_name;*/

        /*$temp_users = array();



        if (file_exists($file))
        {
            if (($file_select = fopen($file, 'r')) !== false)
            {
                $i = 0;
                while (($data = fgetcsv($file_select, 1000, ';')) !== false)
                {

                    $temp_users[$i]['orgName'] = 'Государственное автономное учреждение Пензенской области "Многофункциональный центр предоставления государственных и муниципальных услуг" (дополнительный офис) (г.Пенза, ул. Богданова, д. 63А)';
                    $temp_users[$i]['orgCode'] = 'MFC-000002536';

                    $temp_users[$i]['_id'] = _CreateRandomHex(4).'-'._CreateRandomHex(2).'-'._CreateRandomHex(2).'-'._CreateRandomHex(2).'-'._CreateRandomHex(6);
                    $temp_users[$i]['surName'] = $data[3];
                    $temp_users[$i]['firstName'] = $data[4];
                    $temp_users[$i]['patronymic'] = $data[5];
                    $temp_users[$i]['job'] = $data[6];
                    $temp_users[$i]['login'] = mb_strtolower($data[7], 'UTF-8');
                    $temp_users[$i]['password'] = $user->createPVDFormatPassword($data[8]);

                    $temp_users[$i]['_class'] = 'ru.atc.pvd.rs.mia.model.UserMia';
                    $temp_users[$i]['status'] = 'ACTUAL';


                    $i++;
                }
                fclose($file_select);
            }
        }
        else
        {
            echo 'Не удалось подключить файл';
        }*/

        /*Adm_user::addUsers($temp_users, $file_name);*/


        if (isset($_GET['name']))
        {
            $name = trim($_GET['name']);
        }
        $index_number = ($page - 1) * User::SHOW_BY_DEFAULT;

        $pagination = new Pagination($total, $page, User::SHOW_BY_DEFAULT, 'page=');

        _gt_view:
        if ($is_can)
        {
            $url_param .= 'name='.$search['name'].'&job='.$search['job']
                .'&status='.$search['status'] .'&organization='.$search['organization']
                .'&page='.$page;
            include_once APP_VIEWS.'user/index.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionAdd()
    {
        $user_right = parent::getUserRight();
        $validate = new App_Validate();
        $replace_chars = include (ROOT . '/config/replace_chars.php');
        $url_param = '';
        $is_can = false;
        $search = array();
        $page = 1;
        $errors = false;
        $user = new User();
        $new_user = array();
        $user_organization = array();

        $search['rs_man'] = false;
        $search['rs_admin'] = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r == RS_ADMIN)
            {
                $search['rs_admin'] = true;
                $is_can = true;
            }
            if ($u_r == RS_MAN)
            {
                $search['rs_man'] = true;
                $user_organization = User::getMiaUserOrganization(USER_ID);
                $is_can = true;
            }
            if ($search['rs_admin'] && $search['rs_man'])
            {
                break;
            }
        }

        if (isset($_GET['name']))
        {
            $search['name'] = htmlspecialchars($_GET['name']);
        }
        if (isset($_GET['job']))
        {
            $search['job'] = htmlspecialchars($_GET['job']);
        }
        if (isset($_GET['status']))
        {
            $search['status'] = htmlspecialchars($_GET['status']);
        }
        if (isset($_GET['organization']))
        {
            $search['organization'] = htmlspecialchars($_GET['organization']);
        }
        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        $url_param .= 'name='.$search['name'].'&job='.$search['job']
            .'&status='.$search['status'] .'&organization='.$search['organization']
            .'&page='.$page;

        $new_user = array(
            "_id" => $user->createPVDFormatUserID(),
            "_class" => 'ru.atc.pvd.rs.mia.model.UserMia',
            "status" => STATUS_ACTUAL,
        );

        if (!$search['rs_admin'])
        {
            $new_user['orgCode'] = $user_organization['orgCode'];
            $new_user['orgName'] = $user_organization['orgName'];
        }

        $html_element['lastname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['lastname']->setName('lastname');
        $html_element['lastname']->setId('lastname');
        $html_element['lastname']->setMin(1);
        $html_element['lastname']->setMax(128);
        $html_element['lastname']->setCaption('Фамилия');
        $html_element['lastname']->setConfig('type', 'text');
        $html_element['lastname']->setConfig('class', 'uk-width-1-1');
        $html_element['lastname']->setConfig('placeholder', 'Фамилия');
        $html_element['lastname']->setValueFromRequest();

        $html_element['firstname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['firstname']->setName('firstname');
        $html_element['firstname']->setId('firstname');
        $html_element['firstname']->setMin(1);
        $html_element['firstname']->setMax(64);
        $html_element['firstname']->setCaption('Имя');
        $html_element['firstname']->setConfig('type', 'text');
        $html_element['firstname']->setConfig('class', 'uk-width-medium-1-1');
        $html_element['firstname']->setConfig('placeholder', 'Имя');
        $html_element['firstname']->setValueFromRequest();

        $html_element['middlename'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['middlename']->setName('middlename');
        $html_element['middlename']->setId('middlename');
        $html_element['middlename']->setMax(128);
        $html_element['middlename']->setCaption('Отчество');
        $html_element['middlename']->setConfig('type', 'text');
        $html_element['middlename']->setConfig('class', 'uk-width-1-1');
        $html_element['middlename']->setConfig('placeholder', 'Отчество');
        $html_element['middlename']->setValueFromRequest();

        $html_element['login'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['login']->setName('login');
        $html_element['login']->setId('login');
        $html_element['login']->setMin(3);
        $html_element['login']->setMax(64);
        $html_element['login']->setCaption('Логин');
        $html_element['login']->setConfig('type', 'text');
        $html_element['login']->setConfig('class', 'uk-width-1-1');
        $html_element['login']->setConfig('placeholder', 'Логин');
        $html_element['login']->setValueFromRequest();

        $html_element['password'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['password']->setName('password');
        $html_element['password']->setId('password');
        $html_element['password']->setMin(6);
        $html_element['password']->setMax(40);
        $html_element['password']->setCaption('Пароль');
        $html_element['password']->setConfig('type', 'password');
        $html_element['password']->setConfig('class', 'uk-width-1-1');
        $html_element['password']->setConfig('placeholder', 'Пароль');
        $html_element['password']->setValueFromRequest();

        $organiztaions = Organization::getAdmPvdOrganizationsByStatus(STATUS_ACTUAL);

        $option_organization = array();

        $i = 0;
        $option_organization[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_organization[$i]->setText('[выбрать]');

        $temp_organizations = array();

        $i = 1;
        foreach ($organiztaions as $value)
        {
            if ($value['status'] == STATUS_ACTUAL)
            {
                $option_organization[$i] = new \HTMLElement\HTMLSelectOptionElement();
                $option_organization[$i]->setValue($value['code']);
                $option_organization[$i]->setText($value['name'].' ['.$value['code'].']');
                $temp_organizations[$value['code']] = $value['name'];
            }
            $i++;
        }

        $html_element['organization'] = new \HTMLElement\HTMLSelectElement($option_organization);
        $html_element['organization']->setCaption('Организация [код организации]');
        $html_element['organization']->setName('organization');
        $html_element['organization']->setId('organization');
        $html_element['organization']->setNecessarily(true);
        $html_element['organization']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['organization']->setConfig('class', 'uk-width-1-1 chosen-select');
        $html_element['organization']->setDefaultValue($user_organization['orgCode']);
        $html_element['organization']->setValueFromRequest();

        if (!$search['rs_admin'])
        {
            $html_element['organization']->setConfig('disabled', 'disabled');
        }

        $roles = array();
        if ($search["rs_admin"])
        {
            $roles[RS_ADMIN] = 'Администратор';
        }
        $roles[RS_MAN] = 'Руководитель офиса';
        $roles[RS_DISP] = 'Диспетчер';
        $roles[RS_RECEPTION] = 'Оператор приёма';
        $roles[RS_DELIVERY] = 'Оператор выдачи';
        $roles[RS_SCAN] = 'Оператор сканирования';

        $i = 1;
        foreach ($roles as $r_key => $r_value)
        {
            $html_element['roles_'.$i] = new \HTMLElement\HTMLCheckboxAndRadioCheckboxElement();
            $html_element['roles_'.$i]->setName('roles[]');
            $html_element['roles_'.$i]->setValue($r_key);
            $html_element['roles_'.$i]->setId('role_'.$i);
            $html_element['roles_'.$i]->setNecessarily(false);
            $html_element['roles_'.$i]->setCaption($r_value);
            $html_element['roles_'.$i]->setCheckedFromRequest();

            $i++;
        }

        $html_element['job'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['job']->setName('job_0');
        $html_element['job']->setId('job_0');
        $html_element['job']->setMin(1);
        $html_element['job']->setMax(256);
        $html_element['job']->setCaption('Должность');
        $html_element['job']->setConfig('type', 'text');
        $html_element['job']->setConfig('class', 'uk-width-1-1');
        $html_element['job']->setConfig('placeholder', 'Должность');
        $html_element['job']->setConfig('autocomplete', 'off');
        $html_element['job']->setValueFromRequest();

        if (isset($_POST['add']))
        {
            $lnSegments = [];
            $lnSegments = explode("-", $html_element['lastname']->getValue());
            $toImplode = [];
            foreach($lnSegments as $segments)
            {
                $toImplode[] = trim($validate->my_ucwords($segments));
            }
            $lastname = implode("-", $toImplode);
            $lastname = str_ireplace($replace_chars, "", $lastname);
            $html_element['lastname']->setValue($lastname);
            $html_element['firstname']->setValue(trim($validate->my_ucwords($html_element['firstname']->getValue())));
            $html_element['middlename']->setValue(trim($validate->my_ucwords($html_element['middlename']->getValue())));
            $html_element['login']->setValue(trim(mb_strtolower($html_element['login']->getValue())));

            $html_element['lastname']->check();
            $html_element['firstname']->check();
            $html_element['middlename']->check();
            $html_element['login']->check();
            $html_element['password']->check();
            $html_element['job']->check();

            if (!$html_element['lastname']->getCheck())
            {
                $errors['lastname'] = 'Ошибка в поле "'.$html_element['lastname']->getCaption().'".';
            }
            if (!$html_element['firstname']->getCheck())
            {
                $errors['firstname'] = 'Ошибка в поле "'.$html_element['firstname']->getCaption().'".';
            }
            if (!$html_element['middlename']->getCheck())
            {
                $errors['middlename'] = 'Ошибка в поле "'.$html_element['middlename']->getCaption().'".';
            }
            if (!User::checkMiaLogin($html_element['login']->getValue()))
            {
                $html_element['login']->setCheck(false);
                $errors['login_db'] = 'Пользователь с таким логином уже зарегистрирован';
            }

            if (!$validate->checkLogin($html_element['login']->getValue()))
            {
                $html_element['login']->setCheck(false);
            }
            if (!$html_element['login']->getCheck())
            {
                $errors['login'] = 'Ошибка в поле "'. $html_element['login']->getCaption() .'"';
            }

            if (!$html_element['job']->getCheck())
            {
                $errors['job'] = 'Ошибка в поле "'. $html_element['job']->getCaption() .'"';
            }

            if ($search['rs_admin'])
            {
                $html_element['organization']->check();
                if (!$html_element['organization']->getCheck())
                {
                    $errors['organization'] = 'Ошибка в поле "' . $html_element['organization']->getCaption() . '".';
                }
            }

            $temp_roles = array();

            $i = 1;
            foreach ($roles as $r_key => $r_value)
            {
                $html_element['roles_'.$i]->check();
                if (!$html_element['roles_'.$i]->getCheck())
                {
                    $errors['roles'] = 'Ошибка при установке прав';
                }

                if ($html_element['roles_'.$i]->getChecked())
                {
                    $temp_roles[] = $r_key;
                }
                $i++;
            }

            if (!$html_element['password']->getCheck())
            {
                $errors['password'] = 'Ошибка в поле "'. $html_element['password']->getCaption() .'".<br />
                Необходимо заполнить от 6 до 20 символов.';
            }

            if ($errors === false)
            {
                $new_user['login'] = $html_element['login']->getValue();
                $new_user['surName'] = $html_element['lastname']->getValue();
                $new_user['firstName'] = $html_element['firstname']->getValue();
                $new_user['patronymic'] = $html_element['middlename']->getValue();
                $new_user['job'] = $html_element['job']->getValue();
                $new_user['password'] = $user->createPVDFormatPassword($html_element['password']->getValue());
                $new_user['roles'] = $temp_roles;
                if (!$search['rs_admin'])
                {
                    $new_user['orgCode'] = $user_organization['orgCode'];
                    $new_user['orgName'] = $user_organization['orgName'];
                }
                else
                {
                    $new_user['orgCode'] = $html_element['organization']->getValue();
                    $new_user['orgName'] = $temp_organizations[$new_user['orgCode']];
                }
                User::addMiaUser($new_user);
                header('Location: /user/index?'.$url_param);
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/add.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionEdit()
    {
        $user_right = parent::getUserRight();
        $validate = new App_Validate();
        $replace_chars = include (ROOT . '/config/replace_chars.php');
        $url_param = '';
        $is_can = false;
        $search = array();
        $page = 1;
        $errors = false;
        $user = new User();
        $this_user = array();
        $user_organization = array();
        $uid = null;

        $search['rs_man'] = false;
        $search['rs_admin'] = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r == RS_ADMIN)
            {
                $search['rs_admin'] = true;
                $is_can = true;
            }
            if ($u_r == RS_MAN)
            {
                $search['rs_man'] = true;
                $user_organization = User::getMiaUserOrganization(USER_ID);
                $is_can = true;
            }
            if ($search['rs_admin'] && $search['rs_man'])
            {
                break;
            }
        }

        if (isset($_GET['name']))
        {
            $search['name'] = htmlspecialchars($_GET['name']);
        }
        if (isset($_GET['job']))
        {
            $search['job'] = htmlspecialchars($_GET['job']);
        }
        if (isset($_GET['status']))
        {
            $search['status'] = htmlspecialchars($_GET['status']);
        }
        if (isset($_GET['organization']))
        {
            $search['organization'] = htmlspecialchars($_GET['organization']);
        }
        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }
        if (isset($_GET['uid']))
        {
            $uid = htmlspecialchars($_GET['uid']);
        }

        if (!$search['rs_admin'])
        {
            $this_user = User::getMiaUser($uid, $user_organization['orgCode']);
        }
        else
        {
            $this_user = User::getMiaUser($uid);
        }

        $user_change_flag = true;
        foreach ($this_user['roles'] as $u_value)
        {
            if ($u_value == RS_ADMIN && $uid != USER_ID)
            {
                $user_change_flag = false;
            }
        }

        if (!$user_change_flag)
        {
            $errors['no_change'] = 'Невозможно изменить данного пользователя';
        }

        $url_param .= 'name='.$search['name'].'&job='.$search['job']
            .'&status='.$search['status'] .'&organization='.$search['organization']
            .'&page='.$page;

        if (!$search['rs_admin'])
        {
            $this_user['orgCode'] = $user_organization['orgCode'];
            $this_user['orgName'] = $user_organization['orgName'];
        }

        $html_element['lastname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['lastname']->setName('lastname');
        $html_element['lastname']->setId('lastname');
        $html_element['lastname']->setMin(1);
        $html_element['lastname']->setMax(128);
        $html_element['lastname']->setCaption('Фамилия');
        $html_element['lastname']->setConfig('type', 'text');
        $html_element['lastname']->setConfig('class', 'uk-width-1-1');
        $html_element['lastname']->setValue($this_user['surName']);
        $html_element['lastname']->setConfig('placeholder', 'Фамилия');
        $html_element['lastname']->setValueFromRequest();

        $html_element['firstname'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['firstname']->setName('firstname');
        $html_element['firstname']->setId('firstname');
        $html_element['firstname']->setMin(1);
        $html_element['firstname']->setMax(64);
        $html_element['firstname']->setCaption('Имя');
        $html_element['firstname']->setConfig('type', 'text');
        $html_element['firstname']->setConfig('class', 'uk-width-medium-1-1');
        $html_element['firstname']->setValue($this_user['firstName']);
        $html_element['firstname']->setConfig('placeholder', 'Имя');
        $html_element['firstname']->setValueFromRequest();

        $html_element['middlename'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['middlename']->setName('middlename');
        $html_element['middlename']->setId('middlename');
        $html_element['middlename']->setMax(128);
        $html_element['middlename']->setCaption('Отчество');
        $html_element['middlename']->setConfig('type', 'text');
        $html_element['middlename']->setConfig('class', 'uk-width-1-1');
        $html_element['middlename']->setValue($this_user['patronymic']);
        $html_element['middlename']->setConfig('placeholder', 'Отчество');
        $html_element['middlename']->setValueFromRequest();

        $html_element['login'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['login']->setName('login');
        $html_element['login']->setId('login');
        $html_element['login']->setMin(3);
        $html_element['login']->setMax(64);
        $html_element['login']->setCaption('Логин');
        $html_element['login']->setConfig('type', 'text');
        $html_element['login']->setConfig('class', 'uk-width-1-1');
        $html_element['login']->setValue($this_user['login']);
        $html_element['login']->setConfig('placeholder', 'Логин');
        $html_element['login']->setValueFromRequest();

        $organiztaions = Organization::getAdmPvdOrganizationsByStatus(STATUS_ACTUAL);

        $option_organization = array();

        $i = 0;
        $option_organization[$i] = new \HTMLElement\HTMLSelectOptionElement();
        $option_organization[$i]->setText('[выбрать]');

        $temp_organizations = array();

        $i = 1;
        foreach ($organiztaions as $value)
        {
            if ($value['status'] == STATUS_ACTUAL)
            {
                $option_organization[$i] = new \HTMLElement\HTMLSelectOptionElement();
                $option_organization[$i]->setValue($value['code']);
                $option_organization[$i]->setText($value['name'].' ['.$value['code'].']');
                $temp_organizations[$value['code']] = $value['name'];
            }
            $i++;
        }

        $html_element['organization'] = new \HTMLElement\HTMLSelectElement($option_organization);
        $html_element['organization']->setCaption('Организация [код организации]');
        $html_element['organization']->setName('organization');
        $html_element['organization']->setId('organization');
        $html_element['organization']->setNecessarily(true);
        $html_element['organization']->setConfig('data-placeholder', 'Не выбрано');
        $html_element['organization']->setConfig('class', 'uk-width-1-1 chosen-select');
        $html_element['organization']->setDefaultValue($this_user['orgCode']);
        $html_element['organization']->setValueFromRequest();

        $html_element['job'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['job']->setName('job_0');
        $html_element['job']->setConfig('type', 'text');
        $html_element['job']->setId('job_0');
        $html_element['job']->setMin(1);
        $html_element['job']->setMax(256);
        $html_element['job']->setCaption('Должность');
        $html_element['job']->setConfig('class', 'uk-width-1-1');
        $html_element['job']->setValue($this_user["job"]);
        $html_element['job']->setConfig('placeholder', 'Должность');
        $html_element['job']->setValueFromRequest();

        if (!$search['rs_admin'])
        {
            $html_element['organization']->setConfig('disabled', 'disabled');
        }

        $roles = array();
        if ($search["rs_admin"])
        {
            $roles[RS_ADMIN] = 'Администратор';
        }
        $roles[RS_MAN] = 'Руководитель офиса';
        $roles[RS_DISP] = 'Диспетчер';
        $roles[RS_RECEPTION] = 'Оператор приёма';
        $roles[RS_DELIVERY] = 'Оператор выдачи';
        $roles[RS_SCAN] = 'Оператор сканирования';

        $i = 1;
        foreach ($roles as $r_key => $r_value)
        {
            $html_element['roles_'.$i] = new \HTMLElement\HTMLCheckboxAndRadioCheckboxElement();
            $html_element['roles_'.$i]->setName('roles[]');
            $html_element['roles_'.$i]->setValue($r_key);
            $html_element['roles_'.$i]->setId('role_'.$i);
            $html_element['roles_'.$i]->setNecessarily(false);
            $html_element['roles_'.$i]->setCaption($r_value);
            if (in_array($r_key, $this_user['roles']))
            {
                $html_element['roles_'.$i]->setChecked(true);
            }
            else
            {
                $html_element['roles_'.$i]->setChecked(false);
            }
            $html_element['roles_'.$i]->setCheckedFromRequest();
            if (!$user_change_flag)
            {
                $html_element['roles_'.$i]->setConfig('disabled', 'disabled');
            }

            $i++;
        }

        if (!$user_change_flag)
        {
            $html_element['lastname']->setDisabled(true);
            $html_element['firstname']->setDisabled(true);
            $html_element['middlename']->setDisabled(true);
            $html_element['login']->setDisabled(true);
            $html_element['job']->setDisabled(true);
            $html_element['organization']->setConfig('disabled', 'disabled');
        }

        if (isset($_POST['edit']))
        {
            $lnSegments = [];
            $lnSegments = explode("-", $html_element['lastname']->getValue());
            $toImplode = [];
            foreach($lnSegments as $segments)
            {
                $toImplode[] = trim($validate->my_ucwords($segments));
            }
            $lastname = implode("-", $toImplode);
            $lastname = str_ireplace($replace_chars, "", $lastname);
            $html_element['lastname']->setValue($lastname);
            $html_element['firstname']->setValue(trim($validate->my_ucwords($html_element['firstname']->getValue())));
            $html_element['middlename']->setValue(trim($validate->my_ucwords($html_element['middlename']->getValue())));
            $html_element['login']->setValue(trim(mb_strtolower($html_element['login']->getValue())));

            $html_element['lastname']->check();
            $html_element['firstname']->check();
            $html_element['middlename']->check();
            $html_element['login']->check();
            $html_element['job']->check();

            if (!$html_element['lastname']->getCheck())
            {
                $errors['lastname'] = 'Ошибка в поле "'.$html_element['lastname']->getCaption().'".';
            }
            if (!$html_element['firstname']->getCheck())
            {
                $errors['firstname'] = 'Ошибка в поле "'.$html_element['firstname']->getCaption().'".';
            }
            if (!$html_element['middlename']->getCheck())
            {
                $errors['middlename'] = 'Ошибка в поле "'.$html_element['middlename']->getCaption().'".';
            }
            if (!User::checkMiaLogin($html_element['login']->getValue()))
            {
                if ($uid != $this_user['_id'])
                {
                    $html_element['login']->setCheck(false);
                    $errors['login_db'] = 'Пользователь с таким логином уже зарегистрирован';
                }
            }

            if (!$html_element['login']->getCheck())
            {
                $errors['login'] = 'Ошибка в поле "'. $html_element['login']->getCaption() .'"';
            }

            if (!$html_element['job']->getCheck())
            {
                $errors['job'] = 'Ошибка в поле "'. $html_element['job']->getCaption() .'"';
            }

            if ($search['rs_admin'])
            {
                $html_element['organization']->check();
                if (!$html_element['organization']->getCheck())
                {
                    $errors['organization'] = 'Ошибка в поле "' . $html_element['organization']->getCaption() . '".';
                }
            }

            $temp_roles = array();

            if (isset($_POST['roles']))
            {
                $i = 1;
                foreach ($_POST['roles'] as $r_key => $r_value)
                {
                    if (array_key_exists($r_value, $roles))
                    {
                        $temp_roles[] = $r_value;
                    }
                    $i++;
                }
            }


            // УДАЛИТЬ РОЛИ
            User::deleteMiaOldUserRoles($this_user['_id'], $this_user['roles']);

            $temp_user_data = array();
            $temp_user_data['_id'] = $this_user['_id'];
            $temp_user_data['_class'] = $this_user['_class'];
            $temp_user_data['login'] = $html_element['login']->getValue();
            $temp_user_data['surName'] = $html_element['lastname']->getValue();
            $temp_user_data['firstName'] =$html_element['firstname']->getValue();
            $temp_user_data['patronymic'] = $html_element['middlename']->getValue();
            $temp_user_data['job'] = $html_element['job']->getValue();
            $temp_user_data['status'] = $this_user['status'];
            $temp_user_data['password'] = $this_user['password'];
            $temp_user_data['roles'] = $temp_roles;
            if (!$search['rs_admin'])
            {
                $temp_user_data['orgCode'] = $user_organization['orgCode'];
                $temp_user_data['orgName'] = $user_organization['orgName'];
            }
            else
            {
                $temp_user_data['orgCode'] = $html_element['organization']->getValue();
                $temp_user_data['orgName'] = $temp_organizations[$temp_user_data['orgCode']];
            }

            // ОБНОВИТЬ ДАННЫЕ
            User::editMiaUser($temp_user_data);
            header('Location: /user/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/edit.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionDelete()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $user = new User();
        $uid = null;

        $search['rs_man'] = false;
        $search['rs_admin'] = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r == RS_ADMIN)
            {
                $search['rs_admin'] = true;
                $is_can = true;
            }
            if ($u_r == RS_MAN)
            {
                $search['rs_man'] = true;
                $user_organization = User::getMiaUserOrganization(USER_ID);
                $is_can = true;
            }
            if ($search['rs_admin'] && $search['rs_man'])
            {
                break;
            }
        }

        if (isset($_GET['name']))
        {
            $search['name'] = htmlspecialchars($_GET['name']);
        }
        if (isset($_GET['job']))
        {
            $search['job'] = htmlspecialchars($_GET['job']);
        }
        if (isset($_GET['status']))
        {
            $search['status'] = htmlspecialchars($_GET['status']);
        }
        if (isset($_GET['organization']))
        {
            $search['organization'] = htmlspecialchars($_GET['organization']);
        }
        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        if (isset($_GET['uid']))
        {
            $uid = htmlspecialchars($_GET['uid']);
        }

        $url_param .= 'name='.$search['name'].'&job='.$search['job']
            .'&status='.$search['status'] .'&organization='.$search['organization'];

        if (!$search['rs_admin'])
        {
            $this_user = User::getMiaUser($uid, $user_organization['orgCode']);
        }
        else
        {
            $this_user = User::getMiaUser($uid);
        }

        $user_password_change_flag = true;
        foreach ($this_user['roles'] as $u_value)
        {
            if ($u_value == RS_ADMIN)
            {
                $user_password_change_flag = false;
            }
        }

        if ($this_user['status'] == STATUS_ARCHIVE)
        {
            $user_password_change_flag = false;
            $errors['status'] = 'Данный пользователь уже в архиве';
        }

        if (!$user_password_change_flag)
        {
            $errors['no_change'] = 'Невозможно изменить данного пользователя';
        }

        if (isset($_POST['yes']))
        {
            if ($errors === false)
            {
                User::changeMiaUserStatus($uid, STATUS_ARCHIVE);
                $total = 0;
                User::getMiaUsers($search, $page, $total);
                if ($total <= User::SHOW_BY_DEFAULT)
                {
                    $page = 1;
                }
                $url_param .= '&page='.$page;
                header('Location: /user/index?'.$url_param);
            }
        }
        $url_param .= '&page='.$page;
        if (isset($_POST['no']))
        {
            header('Location: /user/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/delete.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionRestore()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $search = [];
        $page = 1;
        $errors = false;
        $user = new User();
        $uid = null;

        $search['rs_man'] = false;
        $search['rs_admin'] = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r == RS_ADMIN)
            {
                $search['rs_admin'] = true;
                $is_can = true;
            }
            if ($u_r == RS_MAN)
            {
                $search['rs_man'] = true;
                $user_organization = User::getMiaUserOrganization(USER_ID);
                $is_can = true;
            }
            if ($search['rs_admin'] && $search['rs_man'])
            {
                break;
            }
        }

        if (isset($_GET['name']))
        {
            $search['name'] = htmlspecialchars($_GET['name']);
        }
        if (isset($_GET['job']))
        {
            $search['job'] = htmlspecialchars($_GET['job']);
        }
        if (isset($_GET['status']))
        {
            $search['status'] = htmlspecialchars($_GET['status']);
        }
        if (isset($_GET['organization']))
        {
            $search['organization'] = htmlspecialchars($_GET['organization']);
        }
        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        if (isset($_GET['uid']))
        {
            $uid = htmlspecialchars($_GET['uid']);
        }

        $url_param .= 'name='.$search['name'].'&job='.$search['job']
            .'&status='.$search['status'] .'&organization='.$search['organization']
            .'&page='.$page;

        if (!$search['rs_admin'])
        {
            $this_user = User::getMiaUser($uid, $user_organization['orgCode']);
        }
        else
        {
            $this_user = User::getMiaUser($uid);
        }

        $user_password_change_flag = true;
        foreach ($this_user['roles'] as $u_value)
        {
            if ($u_value == RS_ADMIN)
            {
                $user_password_change_flag = false;
            }
        }

        if ($this_user['status'] == STATUS_ACTUAL)
        {
            $user_password_change_flag = false;
            $errors['status'] = 'У данного пользователя актуальная учетная запись';
        }

        if (!$user_password_change_flag)
        {
            $errors['no_change'] = 'Невозможно изменить данного пользователя';
        }

        if (isset($_POST['yes']))
        {
            if ($errors === false)
            {
                User::changeMiaUserStatus($uid, STATUS_ACTUAL);
                header('Location: /user/index?'.$url_param);
            }
        }
        if (isset($_POST['no']))
        {
            header('Location: /user/index?'.$url_param);
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/restore.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }

    public function actionPassword()
    {
        $user_right = parent::getUserRight();
        $url_param = '';
        $is_can = false;
        $search = array();
        $page = 1;
        $errors = false;
        $user = new User();
        $new_user = array();
        $user_organization = array();
        $uid = null;

        $search['rs_man'] = false;
        $search['rs_admin'] = false;

        foreach ($user_right as $u_r)
        {
            if ($u_r == RS_ADMIN)
            {
                $search['rs_admin'] = true;
                $is_can = true;
            }
            if ($u_r == RS_MAN)
            {
                $search['rs_man'] = true;
                $user_organization = User::getMiaUserOrganization(USER_ID);
                $is_can = true;
            }
            if ($search['rs_admin'] && $search['rs_man'])
            {
                break;
            }
        }

        if (isset($_GET['name']))
        {
            $search['name'] = htmlspecialchars($_GET['name']);
        }
        if (isset($_GET['job']))
        {
            $search['job'] = htmlspecialchars($_GET['job']);
        }
        if (isset($_GET['status']))
        {
            $search['status'] = htmlspecialchars($_GET['status']);
        }
        if (isset($_GET['organization']))
        {
            $search['organization'] = htmlspecialchars($_GET['organization']);
        }
        if (isset($_GET['page']))
        {
            $page = intval(htmlspecialchars($_GET['page']));
            if ($page < 1)
            {
                $page = 1;
            }
        }

        if (isset($_GET['uid']))
        {
            $uid = htmlspecialchars($_GET['uid']);
        }

        $url_param .= 'name='.$search['name'].'&job='.$search['job']
            .'&status='.$search['status'] .'&organization='.$search['organization']
            .'&page='.$page;

        if (!$search['rs_admin'])
        {
            $this_user = User::getMiaUser($uid, $user_organization['orgCode']);
        }
        else
        {
            $this_user = User::getMiaUser($uid);
        }

        $html_element['password'] = new \HTMLElement\HTMLTextStringElement();
        $html_element['password']->setName('password');
        $html_element['password']->setId('password');
        $html_element['password']->setMin(6);
        $html_element['password']->setMax(40);
        $html_element['password']->setCaption('Пароль');
        $html_element['password']->setConfig('type', 'password');
        $html_element['password']->setConfig('class', 'uk-width-1-1');
        $html_element['password']->setConfig('placeholder', 'Пароль');
        $html_element['password']->setValueFromRequest();

        $user_password_change_flag = true;
        foreach ($this_user['roles'] as $u_value)
        {
            if ($u_value == RS_ADMIN && USER_ID != $uid)
            {
                $user_password_change_flag = false;
            }
        }

        if (!$user_password_change_flag)
        {
            $errors['no_change'] = 'Невозможно изменить данного пользователя';
            $html_element['password']->setConfig('disabled', 'disabled');
        }

        if (isset($_POST['edit']))
        {
            $html_element['password']->check();
            if (!$html_element['password']->getCheck())
            {
                $errors['password'] = 'Ошибка в поле "'. $html_element['password']->getCaption() .'".<br />
                Необходимо заполнить от 6 до 20 символов.';
            }

            if ($errors === false)
            {
                $this_user['password'] = $user->createPVDFormatPassword($html_element['password']->getValue());
                $this_user['_id'] = $uid;

                User::editPassword($this_user);
                header('Location: /user/index?'.$url_param);
            }
        }

        if ($is_can)
        {
            include_once APP_VIEWS.'user/password.php';
        }
        else
        {
            header('Location: /main/error');
        }
    }
}