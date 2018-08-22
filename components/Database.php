<?php

/**
 * Компонент для работы с базой данных
 */
class Database
{

    /**
     * Устанавливает соединение с базой данных
     * @return \MongoClient() <p>Объект класса PDO для работы с БД</p>
     */
    public static function getConnection()
    {
        global $DB_PARAMETERS;
        try
        {
            // Устанавливаем соединение
            $db = new MongoClient($DB_PARAMETERS['host'].":".$DB_PARAMETERS['port']);

            return $db;
        }
        catch (MongoException $e)
        {
            $errors['no_connection'] = 'Не удалось подключиться к базе данных. Дальнейшая работа приложения не возможна. <br>'.iconv( "cp1251","UTF-8", $e->getMessage());
            $error_file = ROOT . '/app/views/error/error.php';
            if (file_exists($error_file))
            {
                include_once $error_file;
            }
            else
            {
                echo $errors['no_connection'];
            }
            exit();
        }
    }
}
