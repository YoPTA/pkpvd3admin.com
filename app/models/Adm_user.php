<?php


class Adm_user
{
    /*****************************************************
     ******************** ПОЛЯ КЛАССА ********************
     *****************************************************/

    const SHOW_BY_DEFAULT = 20;

    /*****************************************************
     ******************* МЕТОДЫ КЛАССА *******************
     *****************************************************/

    public static function getMiaUsers($search = null)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        /*$regex = new MongoRegex("/nikolaevama/i");

        $find_param = array("login"=> $regex);
        $sort_param = array("surName"=>1);

        $temp_result = $collection->find($find_param)->limit(self::SHOW_BY_DEFAULT)->skip(0)->sort($sort_param);*/



        $temp_result = $collection->find()/*->distinct("_id")*/;
        $result = array();
        while ($user = $temp_result->getNext())
        {
            $result[] = $user;
        }
        $db->close();
        return $result;
    }

    public static function updateUser()
    {
        function _CreateRandomHex($length)
        {
            $result = '';
            for ($i = 0; $i < $length; $i++)
            {
                $result = $result.sprintf("%02x", rand(0, 255));
            }
            return $result;
        }
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;
        $oldDocs=array("_id"=> "5b5ec631052effa03000009f");
        $newDoc = array(
            "_id" =>  _CreateRandomHex(4).'-'._CreateRandomHex(2).'-'._CreateRandomHex(2).'-'._CreateRandomHex(2).'-'._CreateRandomHex(6),
            "surName" => "Неколаева",
        );
        $option = array("upsert" => false);

        $collection->update($oldDocs, $newDoc, $option);
        $db->close();
    }

    public static function deleteUsers($users)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;
        foreach ($users as $user)
        {
            $forDelete = array("_id" => $user['_id']);
            // дополнительные опции для удаления
            $options = array ('justOne' => true);
            $collection->remove($forDelete, $options);
        }
        $db->close();

    }


    public static function getLoginUsers($search = null)
    {
        global $DB_PARAMETERS;
        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $find_param = array("login"=> 1);
        $temp_result = $collection->find(array(), $find_param);

        $result = array();
        while ($user = $temp_result->getNext())
        {
            $result[] = $user['login'];
        }
        $db->close();
        return $result;
    }

    public static function addUsers($users, $file_name = null)
    {
        if (!is_array($users) || count($users) < 1)
        {
            return false;
        }
        global $DB_PARAMETERS;
        $login_users = self::getLoginUsers(null);


        $db = Database::getConnection();
        $collection = $db->$DB_PARAMETERS['database']->mia_user;

        $log_insert_users = array();
        $li = 0;
        $i = 0;
        while ($i < count($users))
        {
            if (!in_array($users[$i]['login'], $login_users))
            {
                $temp_user = array(
                    '_id' => $users[$i]['_id'],
                    '_class' => $users[$i]['_class'],
                    'login' => $users[$i]['login'],
                    'surName' => $users[$i]['surName'],
                    'firstName' => $users[$i]['firstName'],
                    'patronymic' => $users[$i]['patronymic'],
                    'job' => $users[$i]['job'],
                    'status' => $users[$i]['status'],
                    'password' => $users[$i]['password'],
                    'roles' => array(
                        0 => "RS_RECEPTION",
                        1 => "RS_DELIVERY",
                        2 => "RS_SCAN"
                    ),
                    'orgCode' => $users[$i]['orgCode'],
                    'orgName' => $users[$i]['orgName'],
                );
                $collection->insert($temp_user);
                $log_insert_users[$li]['login'] = $users[$i]['login'];
                $log_insert_users[$li]['status'] = 'OK';
            }
            else
            {
                $log_insert_users[$li]['login'] = $users[$i]['login'];
                $log_insert_users[$li]['status'] = 'ERROR';
            }
            $li++;

            $i++;
        }

        if (count($log_insert_users) > 0)
        {
            foreach ($log_insert_users as $fields)
            {
                echo $fields['login'] . ' - ' . $fields['status'];
                echo '<br>';
            }
        }

        $db->close();
        return true;
    }
}