<?php
return [

    'main/index' => ['controller' => 'Main', 'action' => 'index'],
    'main' => ['controller' => 'Main', 'action' => 'index'],
    '' => ['controller' => 'Main', 'action' => 'index'],

    'main/error' => ['controller' => 'Main', 'action' => 'error'],
    'main/login' => ['controller' => 'Main', 'action' => 'login'],
    'main/logout' => ['controller' => 'Main', 'action' => 'logout'],

    'user/index' => ['controller' => 'User', 'action' => 'index'],
    'user/add' => ['controller' => 'User', 'action' => 'add'],
    'user/edit' => ['controller' => 'User', 'action' => 'edit'],
    'user/delete' => ['controller' => 'User', 'action' => 'delete'],
    'user/restore' => ['controller' => 'User', 'action' => 'restore'],
    'user/password' => ['controller' => 'User', 'action' => 'password'],
];