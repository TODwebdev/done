<?php
namespace Config;
use DataProviders\RedisDataProvider;
use DataProviders\RedisExistsMapper;
use DataProviders\RedisGetMapper;
use DataProviders\RedisSetMapper;
use Predis\Client;
use Predis\Autoloader;

/**
 * Class Holds default info for system operability
 * All data are stored in One of the DataProviders (current - Redis)
 */
abstract class Config {
    /**
     * @var Client Redis client instance
     */
    private $redis;
    protected $redisAutoloaderFile = '';
    /**
     * @var
     */
    private $mongo;
    private $mongoDbName = 'todo_list';
    private $mongoCollectionName = 'tasks';

    protected $keySeparator = '_';

    protected $dataStorage = 'RedisDataProvider';     // name of a data provider class

    protected $translations =  [
        'ADD'	=> [
            'EN'	=> 'Add',
            'RU'	=> 'Добавить'
        ],
        'header1'	=> [
            'EN'	=> 'Hello',
            'RU'	=> 'Приветствую'
        ]
    ];

    protected $taskStates = ['new', 'doing', 'done']; // states of task
    // by default user has 1 category - Unsorted and default task duration of 1 hour
    protected $categories = [ 'unsorted' => [ 'name'=>'Unsorted', 'task_duration'=>3600000 ], 'important' => [ 'name'=>'Important', 'task_duration'=>3600000 ] ];

    // which keys should be cached in redis THEY ARE USER SPECIFIC
    protected $initKeys = [ 'userPwd', 'taskStates', 'categories' ];

    public function __construct()
    {
        $this->redisAutoloaderFile = __DIR__ . '\..\Vendor\predis\autoload.php';
        // for the development purposes translations were left in code, but should be taken out
       // include (__DIR__ . '\translations.php');
       // $this->translations = $translations;
        if (!$this->getRedis()->exists('translations')) {
            $this->getRedis()->set('translations', json_encode($this->translations, 111));
        }
        if (!$this->getRedis()->exists('categories')) {
            $this->getRedis()->set('categories', json_encode($this->categories, 111));
    }
    }

    /**
     * If this function is called, this means that there is a need to create keys with default values
     */
    protected function init()
    {
        $redisDataProvider = new RedisDataProvider($this);
            foreach ($this->initKeys as $key=>$type) {
                $keyValMapper = new RedisSetMapper($this->userPwd . $this->keySeparator . $key, $this->key);
                $redisDataProvider->set($keyValMapper);
            }
            $keyValMapper = new RedisSetMapper($this->userPwd.'_'.'init', 1);
            $redisDataProvider->set($keyValMapper);
    }

    public function getUserPwd()
    {
        return $this->userPwd;
    }

    /**
     * Returns instance of Redis client
     * @param string $pathToAutoloader
     * @return Client redis object
     */
    public function getRedis($pathToAutoloader=null)
    {
        if ($this->redis instanceof Client) {

        } else {
            if (null === $pathToAutoloader) {
                require  "$this->redisAutoloaderFile";
            } else {
                require  "$pathToAutoloader";
            }
            Autoloader::register();
            $this->redis = new Client();
        }
        return $this->redis;
    }

    /**
     * Returns instance of \MongoCollection
     * @return \MongoCollection
     */
    public function getMongo()
    {
        if ($this->mongo instanceof \MongoCollection) {

        } else {
            $conn = new \MongoClient();
            $dbName = $this->mongoDbName;
            $db = $conn->$dbName;
            $collName = $this->mongoCollectionName;
            $this->mongo = $db->$collName;
        }
        return $this->mongo;
    }

    /**
     * getter checks TTL of data and retrieves it from data storage
     * We try to find user-specific key in DataProvider, if none - try to find key for default user in DataProvider,
     * if none - try to read protected properties, if none - throw exception
     * @param string $key key of data to retrieve (key does not contain userPwd prefix)
     * @return mixed value of a key or null
     */
    public function __get($key)
    {
        if ($this->getRedis()->exists($key)) {

        }
        $redisDataProvider = new RedisDataProvider($this);
        $existsMapper = new RedisExistsMapper($key);
        $val = $redisDataProvider->exists($existsMapper);
        if (1 !== intval($val)) {
           $res = null;
        } else {
            $getMapper = new RedisGetMapper($key);
            $res = $redisDataProvider->get($getMapper);
        }
        return $res;
    }

    /**
     * writes data into data storage
     */
    public function __set($key, $data)
    {
//        $value = (is_array($data)) ? serialize($this->$key) : $this->$key;
//        $keyValMapper = new RedisSetMapper($this->userPwd . $this->keySeparator . $key, $value);
//        $redisDataProvider->set($keyValMapper);
    }
}