<?php
namespace DataProviders;

use Interfaces\ConfigInterface;
use Interfaces\DataProviderInterface;
use Interfaces\MapperInterface;
use Predis\Client;
use Predis\Autoloader;

class RedisDataProvider implements DataProviderInterface
{

    /*   public function redis () {
    require 'C:\Users\TOD\Documents\OSERVTmp\OpenServer\domains\weather\predis\autoload.php';
    Predis\Autoloader::register();
     try {
        $redis = new Predis\Client();
        //$info = print_r($redis->info(), true);
        if ($redis->ttl('weather')<=0) {
            $weather = $this->process();
            $redis->set('weather', $weather);
            $redis->expire('weather', 5);
        }
    }
    catch (Exception $e) {
        die($e->getMessage());
    }
    return $redis->get('weather');
    }*/
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var Client
     */
    protected $redis;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->redis = $this->config->getRedis();
    }

    /** Query to find data
     * @param MapperInterface $mapper
     * @return mixed
     */
    public function get(MapperInterface $mapper)
    {
        return $this->redis->get($mapper->transform());
    }

    /**
     * Query to insert single data
     * @param MapperInterface $mapper
     * @return mixed
     */
    public function set(MapperInterface $mapper)
    {
        list($key, $value) = $mapper->transform();
        $this->redis->set($key, $value);
        return true;
    }

    /**
     * Check if key exists. Outdated keys return 0
     * @param MapperInterface $mapper
     * @return int
     */
    public function exists(MapperInterface $mapper)
    {
        return $this->redis->exists($mapper->transform());
    }

    /**
     * Check if user data has already been initialized
     * @param string $userPwd
     * @return bool
     */
    public function userExists(string $userPwd)
    {
        return $this->redis->get($userPwd.'_'.'init');
    }
}