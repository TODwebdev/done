<?php
set_include_path( __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR);

$minVer = '5.4.0';
if (version_compare(phpversion(), $minVer, '<')) {
    echo sprintf('You need php version later than %s!', $minVer);
    die();
}

include (__DIR__ . '/../autoload.php');
// all our actions will live in this controller
use Controllers\IndexController;

// client has to supply parameter 'action' with action name
// some actions should be accessible with special http verb
$allowedActions = [
    'index'     => '_GET',
    'getlist'   => '_POST',
    'add'       =>  '_POST'
];

$action = (isset($_GET['action']) && in_array($_GET['action'], array_keys($allowedActions)))
            ? strtolower($_GET['action'])
            : 'index';
$params = $$allowedActions[$action];
/// check if data missed _POST
if (!$params
    && '_POST' === $allowedActions[$action]
) {
    $content = file_get_contents('php://input');
    $params = json_decode($content,true);
}

$controller = new IndexController();

$res = $controller->$action($params);