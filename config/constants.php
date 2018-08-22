<?php
// Получаем параметры подключения из файла
$db_parameters_path = ROOT . '/config/db.php';
$db_parameters = include($db_parameters_path);

// Id пользователя
define('USER_ID', User::checkLogged());

// Права пользователя
define('RS_ADMIN', 'RS_ADMIN'); // Администратор
define('RS_MAN', 'RS_MAN'); // Руководитель офиса
define('RS_DISP', 'RS_DISP'); // Диспетчер
define('RS_RECEPTION', 'RS_RECEPTION'); // Оператор приёма
define('RS_DELIVERY', 'RS_DELIVERY'); // Оператор выдачи
define('RS_SCAN', 'RS_SCAN'); // Оператор сканирования

// Параметры подключения к базе данных
$DB_PARAMETERS['host'] = $db_parameters['host'];
$DB_PARAMETERS['database'] = $db_parameters['database'];
$DB_PARAMETERS['port'] = $db_parameters['port'];


// Статусы приложения
define('STATUS_ACTUAL', 'ACTUAL');
define('STATUS_ARCHIVE', 'ARCHIVE');

// Пути проекта
define('APP_VIEWS', ROOT.'/app/views/');
define('APP_TEMPLATES', '/app/templates/');

// Маршруты по умолчанию
define('ROUTE_LOGIN', '/main/login');
define('ROUTE_MAIN', '/');

// Кодировка со строчной буквы
define('DEFAULT_ENCODING_LOWERCASE', 'utf8');
// Кодировка с прописной буквы
define('DEFAULT_ENCODING_UPPERCASE', 'UTF-8');