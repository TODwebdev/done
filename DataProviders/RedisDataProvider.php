<?php
namespace DataProviders;

use Interfaces\ConfigInterface;
use Interfaces\DataProviderInterface;
use Interfaces\MapperInterface;
use Predis\Client;
use Predis\Autoloader;

class RedisDataProvider implements DataProviderInterface
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var Client
     */
    protected $cacher;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->cacher = $this->config->getCacher();
    }

    /** Query to find data
     * @param MapperInterface $mapper
     * @return mixed
     */
    public function get(MapperInterface $mapper)
    {
        return $this->cacher->get($mapper->transform());
    }

    /**
     * Query to insert single data
     * @param MapperInterface $mapper
     * @return mixed
     */
    public function set(MapperInterface $mapper)
    {
        list($key, $value) = $mapper->transform();
        $this->cacher->set($key, $value);
        return true;
    }

    /**
     * Check if key exists. Outdated keys return 0
     * @param MapperInterface $mapper
     * @return int
     */
    public function exists(MapperInterface $mapper)
    {
        return $this->cacher->exists($mapper->transform());
    }

    /**
     * Check if user data has already been initialized
     * @param string $userPwd
     * @return bool
     */
    public function userExists($userPwd)
    {
        return $this->cacher->get($userPwd.'_'.'init');
    }
}