<?php
return array(
    'cachingSystem' => 'redis',
    'autoloaderInitFile' =>  __DIR__ . '\..\Vendor\predis\autoload.php',

    'database'  => 'mongo', // for now only MongoDB adapter is being developed
    'mongoDbName'   =>  'todo_list',
    'mongoCollectionName' => 'tasks',
);