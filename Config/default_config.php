<?php
return array (
    /**
     * Choose name of a caching system (allowed 'redis', 'memcache', 'filesystem')
     * AND provide php variable with init file path like $memcacheAutoloaderInit
     */
    'cachingSystem' => 'redis',

    /**
     * Path to Redis Autoloader File of system, defined in CachingSystem array key
     */
    'autoloaderInitFile' =>  __DIR__ . '\..\Vendor\predis\autoload.php',

    /**
     * symbol which separates client and general keys in cacher
     */
    'keySeparator' => '_',

    /**
     * Basic translations for the interface
     */
    'translations'  => array('ADD'=>array('EN'=>'CHECK','RU'=>'ПРОВЕРИТЬ'),'header1'=>array('EN'=>'HELLO','RU'	=>'Приветствую')),

    'database'  => 'mongo', // for now only MongoDB adapter is being developed

    'mongoDbName'   =>  'todo_list',

    'mongoCollectionName' => 'tasks',

    // client specific options

    /**
     * Default task states. Client can add his own
     */
    'taskStates' => array('new', 'doing', 'done'),

    /**
     * by default user has 1 categories with task duratoin of 1 hour
     */
    'categories' => array(
        'unsorted' => array('name'=>'Unsorted', 'task_duration'=>3600000 ),
        'important' => array( 'name'=>'Important', 'task_duration'=>3600000 )
    ),

    /**
     *  which keys should be cached in redis THEY ARE USER SPECIFIC
     */
    'initKeys' => array( 'userPwd', 'taskStates', 'categories' ),

);