<?php

class User
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    /**
     * Запоминает пользователя и время начала сессии.
     * @param $userId int - id пользователя
     */
    public static function auth($userId)
    {
        session_start();
        $_SESSION['user'] = $userId;
        $_SESSION['session_start_time'] = time(); // Начало сессии
    }

    /**
     * Проверяет время начала входа пользователя.
     */
    public static function checkSessionTime()
    {
        $time_now = time();
        $time_limit = 86400;
        if ($time_now > $_SESSION['session_start_time'] +  $time_limit )
        {
            // Отчищаем сессию
            $_SESSION = array();
            session_destroy ();
            // Перенаправляем пользователя на главную страницу
            header("Location: /");
        }
    }

    /**
     * Првоеряет авторизовался ли пользователь.
     * @return bool|int
     */
    public static function checkLogged()
    {
        session_start();
        // Если сессия есть, возвращаем id пользователя
        if(isset($_SESSION['user']))
        {
            return $_SESSION['user'];
        }
        return false;
    }


    /**
     * Завершает сессию.
     */
    public static function logout()
    {
        session_start();
        $u_id = User::checkLogged();
        if ($u_id != false)
        {
            $app_directory = new App_Directory();
            $dir_path = '/temp/users';
            $temp_user_dir = ROOT.$dir_path.'/'.$u_id;
            // Удаляем директорию, если она есть
            $app_directory->removeDirectory($temp_user_dir);
        }
        $_SESSION = array();
        session_destroy ();
        // Перенаправляем пользователя на главную страницу
        header("Location: /main/login");
    }



    /**
     * Удалить пользователя (изменить флаг)
     * @param int $id - ID пользователя
     */

    /**
     * Изменить статус пользователя
     * @param int $id - ID пользователя
     * @param string $status - Статус пользователя
     */
    public static function changeMiaUserStatus($id, $status)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $find_parameter = array(
            "_id" => $id
        );

        // новый документ
        $edit_parameter = array(
            '$set' => array(
                "status" => $status
            ),
        );

        // дополнительные параметры обновления
        // если документы по критерию не найдены, то новый документ вставляется
        $option = array("upsert" => false);

        $collection->update($find_parameter, $edit_parameter, $option);
        $db->close();
    }

    /**
     * Изменить пароль пользователя
     * @param [] $user - массив с данными
     */
    public static function editPassword($user)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $find_parameter = array(
            "_id" => $user['_id']
        );

        // новый документ
        $edit_parameter = array(
            '$set' => array(
                "password" => $user['password']
            ),
        );

        // дополнительные параметры обновления
        // если документы по критерию не найдены, то новый документ вставляется
        $option = array("upsert" => false);

        $collection->update($find_parameter, $edit_parameter, $option);
        $db->close();
    }

    /**
     * Првоеряет данные пользователя.
     * @param array() $user_data - данные о пользователе
     * @return bool||int
     */
    public static function checkUserData($user_data)
    {
        if (!is_array($user_data))
        {
            return false;
        }

        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $fields = array("_id"=> 1);
        $query = array(
            'login' => $user_data['login'],
            'password' => $user_data['password'],
            'status' => 'ACTUAL',
        );

        $temp_result = $collection->findOne($query, $fields);

        if ($temp_result['_id'] != null)
        {
            $db->close();
            return $temp_result['_id'];
        }
        $db->close();
        return false;
    }

    /********************************************************/
    /********* Методы, связанные с пользователем MIA ********/
    /********************************************************/

    /**
     * Получить информацию о пользователе
     * @param int $id - ID пользователя
     * @param string $organization - Код организации пользователя
     * @return bool|mixed
     */
    public static function getMiaUser($id, $organization = null)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $find_parameter['_id'] = $id;
        if ($organization != null)
        {
            $find_parameter['orgCode'] = $organization;
        }

        $result = $collection->findOne($find_parameter);

        $db->close();
        return $result;
    }

    /**
     * Получить пользователей (mia_user), удовлетворяющих параметрам поиска
     * @param [] $search - Параметры поиска
     * @param int $page - Номер страницы
     * @param int $total - Общее количество пользователей
     * @return array
     */
    public static function getMiaUsers($search, $page, &$total)
    {
        $page = intval($page);
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $find_parameter = array();
        $sort_parameter = array();

        if ($search['desc'] == null)
        {
            $search['desc'] = 1;
        }

        if ($search['sort'] == 'job')
        {
            if ($search['desc'] == -1)
            {
                $sort_parameter = array("job"=>-1);
            }
            else
            {
                $sort_parameter = array("job"=>1);
            }
        }
        elseif ($search['sort'] == 'org')
        {
            if ($search['desc'] == -1)
            {
                $sort_parameter = array("orgName"=>-1);
            }
            else
            {
                $sort_parameter = array("orgName"=>1);
            }
        }
        else
        {
            if ($search['desc'] == -1)
            {
                $sort_parameter = array("surName"=>-1);
            }
            else
            {
                $sort_parameter = array("surName"=>1);
            }
        }

        //$sort_parameter = array("surName"=>1);
        $value = $search['name'];
        $job = $search['job'];

        $segments = explode(" ", $value);
        if (count($segments) == 3)
        {
            $regex1 = new MongoRegex("/^$segments[0]/i");
            $regex2 = new MongoRegex("/^$segments[1]/i");
            $regex3 = new MongoRegex("/^$segments[2]/i");
            $find_parameter = array(
                "surName" => $regex1,
                "firstName" => $regex2,
                "patronymic" => $regex3,
            );
        }
        if (count($segments) == 2)
        {
            $regex1 = new MongoRegex("/^$segments[0]/i");
            $regex2 = new MongoRegex("/^$segments[1]/i");
            $find_parameter = array(
                "surName" => $regex1,
                "firstName" => $regex2,
            );
        }
        if (count($segments) == 1)
        {
            $regex1 = new MongoRegex("/^$value/i");
            $find_parameter = array(
                '$or' => array(
                    array(
                        'surName' => $regex1,
                    ),
                    array(
                        'firstName' => $regex1,
                    ),
                    array(
                        'patronymic' => $regex1,
                    ),
                    array(
                        'login' => $regex1,
                    )
                ),
            );
        }

        if ($job != null)
        {
            $job_regex = new MongoRegex("/^$job/i");
            $find_parameter['job'] = $job_regex;
        }

        if (!$search['rs_admin'] && $search['rs_man'])
        {
            $find_parameter['orgCode'] = $search['organization'];
        }
        if ($search['rs_admin'])
        {
            if ($search['organization'] != null)
            {
                $find_parameter['orgCode'] = $search['organization'];
            }
        }
        if ($search['status'] != null)
        {
            $find_parameter['status'] = $search['status'];
        }

        $temp_result = $collection->find($find_parameter)->limit(self::SHOW_BY_DEFAULT)->skip($offset)->sort($sort_parameter);
        $result = array();
        while ($user = $temp_result->getNext())
        {
            $result[] = $user;
        }
        $total = $collection->count($find_parameter);
        $db->close();
        return $result;
    }

    /**
     * Добавляет нового пользователя
     * @param array() $user - информация о пользователе
     * @return bool|string
     */
    public static function addMiaUser($user)
    {
        if (!is_array($user) || $user['_id'] == null || $user['_class'] == null
            || $user['login'] == null || $user['surName'] == null || $user['firstName'] == null
            || $user['password'] == null || !is_array($user['roles']))
        {
            return false;
        }

        $roles = array();
        foreach ($user['roles'] as $role)
        {
            $roles[] = $role;
        }

        $new_user = array(
            "_id" => $user['_id'],
            "_class" => $user['_class'],
            "login" => $user['login'],
            "surName" => $user['surName'],
            "firstName" => $user['firstName'],
            "patronymic" => $user['patronymic'],
            "job" => $user['job'],
            "status" => $user['status'],
            "password" => $user['password'],
            "roles" => $roles
        );

        if ($user['orgCode'] != null)
        {
            $new_user["orgCode"] = $user['orgCode'];
            $new_user["orgName"] = $user['orgName'];
        }

        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $collection->insert($new_user);

        $db->close();
        return true;
    }

    /**
     * Изменить данные
     * @param array() $user - информация о пользователе
     * @return bool
     */
    public static function editMiaUser($user)
    {
        if (!is_array($user))
        {
            return false;
        }

        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $find_parameter = array(
            "_id" => $user['_id'],
        );

        $edit_parameter = array(
            "_id" => $user['_id'],
            "_class" => $user['_class'],
            "login" => $user['login'],
            "surName" => $user['surName'],
            "firstName" => $user['firstName'],
            "patronymic" => $user['patronymic'],
            "job" => $user['job'],
            "status" => $user['status'],
            "password" => $user['password'],
            "roles" => $user['roles'],
            "orgCode" => $user['orgCode'],
            "orgName" => $user['orgName']
        );

        $collection->update($find_parameter, $edit_parameter);
        $db->close();
    }

    /**
     * Удалить старые роли пользователя
     * @param int $id - ID пользователя
     * @param array() $roles - старые роли
     * @return bool
     */
    public static function deleteMiaOldUserRoles($id, $roles)
    {
        if ($id == null || !is_array($roles) || count($roles) < 1)
        {
            return false;
        }

        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;
        $criteria = array(
            "_id" => $id
        );
        $new_obj = array(
            '$pullAll' => array(
                'roles' => $roles
            )
        );
        $collection->update($criteria, $new_obj);

        $db->close();
        return true;
    }

    /**
     * Получить организацию пользователя
     * @param int $id - ID пользователя
     * @return array|null
     */
    public static function getMiaUserOrganization($id)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $fields_parameter = array(
            'orgCode' => 1,
            'orgName' => 1,
        );
        $find_parameter = array(
            "_id" => $id
        );
        $result = $collection->findOne($find_parameter);

        $db->close();
        return $result;
    }


    /****************************************************/
    /********* Методы, связанные с пользователем ********/
    /****************************************************/

    /**
     * Возвращает права пользователя по его ID
     * @param int $id - ID пользователя
     * @return null||array()
     */
    public static function getUserRights($id)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;
        // Параметры поиска
        $find_param = array("_id" => $id);
        // Поля для вывода
        $fields_param = array("roles"=> 1);
        $result = $collection->findOne($find_param, $fields_param);
        $db->close();
        return $result['roles'];
    }

    /**
     * Проверить логин на существоание
     * @param string $value - Логин
     * @return bool
     */
    public static function checkMiaLogin($value)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $find_parameter = array(
            'login' => $value,
        );

        $temp_result = $collection->findOne($find_parameter);
        $result = true;
        if ($temp_result['login'] != null)
        {
            $result = false;
        }

        $db->close();
        return $result;
    }

    /**
     * Создать пароль пользователя для ПВД 3
     * @param string $password - Пароль
     * @return string
     */
    public function createPVDFormatPassword($password)
    {
        return crypt($password, '$2a$10$'.strtr(substr(base64_encode(sha1($password).md5($password)), 0, 22), array('+' => '.')));
    }

    /**
     * Создать рандомное HEX значение
     * @param int $length - Длина значения
     * @return string
     */
    private function _CreateRandomHex($length)
    {
        $result = '';
        for ($i = 0; $i < $length; $i++)
        {
            $result = $result.sprintf("%02x", rand(0, 255));
        }
        return $result;
    }

    /**
     * Создать ID пользователя в формате ПК ПВД3
     * @return string
     */
    public function createPVDFormatUserID()
    {
        return $this->_CreateRandomHex(4).'-'.$this->_CreateRandomHex(2).'-'.$this->_CreateRandomHex(2).'-'.$this->_CreateRandomHex(2).'-'.$this->_CreateRandomHex(6);
    }
}