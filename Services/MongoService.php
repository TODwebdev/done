<?php
namespace Services;

use DataProviders\MongoCursorMapper;
use DataProviders\MongoDataProvider;
use DataProviders\MongoFindMapper;
use DataProviders\MongoInsertMapper;
use Interfaces\ConfigInterface;
use Models\Tasks;

class MongoService {


    /**
     * Config Object
     * @var string
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }


    /**
     * Get all of the tasks for current user
     * @param array $params define which tasks to fetch
     * @return array from cursor
     */
    public function getAll($params)
    {
        $tasks = new Tasks($this->config);
        $findSetup = $tasks->getFindSetup($params);

        $mongoMapper = new MongoFindMapper($findSetup);
        $mongo = new MongoDataProvider($this->config);

        $cursor = $mongo->get($mongoMapper);
        $curMapper = new MongoCursorMapper($cursor);
        $arr = $curMapper->transform();
        return $arr;
    }

    /**
     * Add new task into DB
     * @param array $params
     * @return mixed
     */
    public function addTask($params)
    {
        $tasks = new Tasks($this->config);
        $insertSetup = $tasks->getInsertSetup($params);

        $mongoMapper = new MongoInsertMapper($insertSetup);

        $mongo = new MongoDataProvider($this->config);
        $ret = $mongo->set($mongoMapper);

        return $ret;
    }

}