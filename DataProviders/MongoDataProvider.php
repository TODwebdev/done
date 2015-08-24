<?php
namespace DataProviders;


use Interfaces\ConfigInterface;
use Interfaces\DataProviderInterface;
use Interfaces\MapperInterface;

class MongoDataProvider implements DataProviderInterface
{
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /** Query to find data
     * @param MapperInterface $mapper
     * @return Cursor
     */
    public function get(MapperInterface $mapper)
    {
        return $this->config->getDb($this->config->mongoCollectionName)->find($mapper->transform());
    }

    /**
     * Query to insert single data
     * @param MapperInterface $mapper
     * @return mixed
     */
    public function set(MapperInterface $mapper)
    {
        $insert = $mapper->transform();
        return $this->config->getDb($this->config->mongoCollectionName)->insert($insert);
    }
}
