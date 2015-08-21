<?php
namespace Controllers;

use Config\UserConfig;
use Services\AuthService;
use Services\MongoService;
use View\View;


class IndexController
{
    // identifier of a current user
    /**
     * @var UserConfig
     */
    protected $config;

    public function __construct() {
        $authService = new AuthService();
        $pwd = $authService->getUserIdentifier();
        $this->config = new UserConfig($pwd);
    }

    /**
     * Main app screen with tasks if there are any
     * @param array $params parameters sent via http
     */
    public function index($params)
    {
        $ret = [];
        $view = new View('DONE! ToDo tasks app, MongoDB, Redis, localstorage');
        $ret['translations'] = json_encode($this->config->translations);
        $ret['categories'] = json_encode($this->config->categories);
        $mongoService = new MongoService($this->config);
        $ret['tasks'] = $mongoService->getAll($params);
        $view->render($ret);
    }

    /**
     * List of all tasks that fall under conditions
     * @param array $params parameters sent via http
     **/
    public function getlist($params)
    {
        $mongoService = new MongoService($this->config);
        echo json_encode($mongoService->getAll($params));
    }

    /**
     * List of all tasks that fall under conditions
     * @param array $params parameters sent via http
     **/
    public function add($params)
    {
        $mongoService = new MongoService($this->config);
        $mongoService->addTask($params);
        echo json_encode($mongoService->getAll($params));
    }
}