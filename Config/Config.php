<?php
namespace Config;
use DataProviders\MongoDataProvider;
use DataProviders\RedisDataProvider;
use DataProviders\RedisExistsMapper;
use DataProviders\RedisGetMapper;
use DataProviders\RedisSetMapper;
use Exceptions\CacherInitException;
use Exceptions\ConfigKeyException;
use Exceptions\DatabaseInitException;
use Predis\Client;
use Predis\Autoloader;

/**
 * Class Holds default info for system operability
 * All data are stored in One of the DataProviders (current - Redis)
 */
abstract class Config
{

    /**
     * @var string name of a default config file
     */
    private $defaultConfigFile = 'default_config.php';

    /**
     * @var string name of a configuration file
     */
    protected $confFile;

    /**
     * @var DataProviderInterface Client object, accessible via getCacher
     */
    private $cacher;

    /**
     * @return DataProviderInterface obj for cache system
     */
    public function getCacher()
    {
        return $this->cacher;
    }

    /**
     * current instance of DB object
     * @var
     */
    private $db;

    /**
     * @return obj for database interactions
     */
    public function getDb($collectionName)
    {
        if (null === $collectionName) {
            $collectionName = $this->mongoCollectionName;
        }
        $collName = $this->mongoCollectionName;
        $coll = $this->db->$collName;
        return $coll;
    }

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


    /**
     * Constructor gets name of a config file (so we can have different configs
     * @param string $configFile
     * @sets $this->confFile
     * @throws \InvalidArgumentException
     */
    public function __construct($configFile)
    {
        if (is_file($configFile)) {
            $this->confFile = $configFile;
        } else {
            throw new \InvalidArgumentException('Config file name does not point to a file');
        }
    }

    /**
     * Initialize default setup
     * BASIC defaults live in default_config.php
     * Custom defaults override basic
     */
    protected function init()
    {
        try {
            // default setup
            $this->initVars($_SERVER['DOCUMENT_ROOT'] . '\..\Config\\' . $this->defaultConfigFile);
            // override defaults
            $this->initVars($this->confFile);

            // for the development purposes translations were left in code, but should be taken out
            // include (__DIR__ . '\translations.php');
            // $this->translations = $translations;

            $cacher = $this->cachingSystem;
            $this->initCacher($cacher, $this->autoloaderInitFile);

            $db = $this->database;
            $this->initDatabase($db);


            // for the development purposes translations were left in code, but should be taken out
            // include (__DIR__ . '\translations.php');
            // $this->translations = $translations;

//            $cacherObj = $this->getCacher();
//            if (!$cacherObj->exists('translations')) {
//                $cacherObj->set('translations', json_encode($this->translations, 111));
//            }
//            if (!$cacherObj->exists('categories')) {
//                $cacherObj->set('categories', json_encode($this->categories, 111));
//            }

            /// if we need some user-specific initialization
            $this->initUserVars();

        } catch (\CacherInitException $e) {
            /// should we try to initialize cacher with default params here???

            throw new \Exception($e->getMessage());
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @param string $confFile init file name, which contains php assoc array
     * @return bool
     */
    protected function initVars($confFile)
    {
        $vars = include_once($confFile);
        foreach ($vars as $key=>$val) {
            $this->$key = $val;
        }
        return true;
    }

     /**
     * Initialize cacher Object
     * @param string $cacherSystemName
     * @param string $confFileName
     * @sets $this->cacher
     * @return $this->cacher
     * @throws CacherInitException
     */
    protected function initCacher ($cacherSystemName, $confFileName)
    {
        $funcName = 'init'.ucfirst($cacherSystemName).'Factory';
        if (is_callable([$this, $funcName])) {
            $this->cacher = $this->$funcName($confFileName);
            return $this->cacher;
        } else {
            throw new CacherInitException ($cacherSystemName);
        }
    }

    /**
     * initialize cacher object
     * @param string $confFileName path to autoloader file
     * @throws CacherInitException
     * @returns Predis\Client redis client obj
     */
    protected function initRedisFactory ($confFileName)
    {
        if (null === $confFileName) {
            throw new CacherInitException('redis');
        }

        require  "$confFileName";
        Autoloader::register();
        return new Client();
    }

    /**
     * this is dummy function. Should be overloaded
     * @param string $confFileName path to autoloader file
     * @returns  memcache provider
     * @throws CacherInitException
     */
    protected function initMemcacheFactory ($confFileName)
    {
        throw new CacherInitException('memcache');
    }
    /**
     * this is dummy function. Should be overloaded
     * @param string $confFileName path to autoloader file
     * @returns  filesystem provider
     * @throws CacherInitException
     */
    protected function initFilesystemFactory ($confFileName)
    {
        throw new CacherInitException('filesystem');
    }

// database

    /**
     * Initialize database Object
     * @param string $dbSystemName name of a database that should be used (mongo, mysql..)
     * @sets $this->db
     * @return $this->db
     * @throws DatabaseInitException
     */
    protected function initDatabase ($dbSystemName)
    {
        $funcName = 'init'.ucfirst($dbSystemName).'Factory';
        if (is_callable([$this, $funcName])) {
            $this->db = $this->$funcName();
            return $this->db;
        } else {
            throw new DatabaseInitException($dbSystemName);
        }
    }

    /**
     * initialize database object
     * @throws DatabaseInitException
     * @returns mongoDb client obj
     */
    protected function initMongoFactory ()
    {
        $conn = new \MongoClient();
        $dbName = $this->mongoDbName;
        $database = $conn->$dbName;
        //$collName = $this->mongoCollectionName;
        //$coll = $db->$collName;
        return $database;
    }

    /**
     * getter checks TTL of data and retrieves it from cacher
     * We try to find user-specific key in cacher, if none - try to find key for default user key in cacher,
     * if none - try to read protected properties, if none - throw exception
     * @param string $key key of data to retrieve (key does not contain userPwd prefix)
     * @return mixed value of a key or null
     */
    public function __get($key)
    {   // client specific key
        $userkey = $this->getUserPwd() .'_' . $key;    //$this->keySeparator
        $defaultkey = $this->getUserPwd() . '_' . $key;
        // FIXME
        $cacher = $this->getCacher();
        if ($this->getCacher()->exists($userkey)) { // looking for user-specific key

        } elseif ($this->getCacher()->exists($defaultkey)) {

        } else {
            if (isset($this->$key)) {
                $res = $this->$key;
            } else {
                throw new ConfigKeyException($key);
            }
        }
        return $res;
    }

    /**
     * Here You can initialize user-specific stuff, write it in cacher, etc
     * @return mixed
     */
    abstract protected function initUserVars();
}