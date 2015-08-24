<?php
namespace Config;

use DataProviders\RedisDataProvider;
use DataProviders\RedisExistsMapper;
use Interfaces\ConfigInterface;

/**
 * this class holds (and accesses on demand) user-specific info
 */
class UserConfig extends Config implements ConfigInterface
{

    /**
     * Task of this constructor is to check, whether all necessary properties are set in data storage,
     * (if not - set them). Properties should be specific for this current user
     * (initially they default to those of default user)
     */
    public function __construct($pwd, $confFile=null)
    {
        parent::__construct($confFile);

        if (is_string($pwd)
            && !empty($pwd)
        ) {
            $this->userPwd = $pwd;
        }
    }

    /**
     * Initialize default setup
     */
    public function init()
    {
        parent::init();
    }


    /**
     * make shure that all user-specific data exists in cacher,
     * if not - put it there with default values
     * @return bool
     */
    protected function initUserVars()
    {
        /// Now we should check, if userdata already exists in Redis. If not - write it there
        $cacher = $this->getCacher();
        $cacherName = $this->cachingSystem;

        $mapperName = ucfirst($cacherName).'ExistsMapper';
        // FIXME does not automatically load namespaces
        if ('Redis' === ucfirst($cacherName)) {
            $existsMapper = new RedisExistsMapper($this->userPwd.'_'.'init');
        } else  {
            $existsMapper = new $mapperName($this->userPwd.'_'.'init');
        }

        $dataProvider = new RedisDataProvider($this);
        if (!$dataProvider->exists($existsMapper)) {
            /// write client data
            $setterMapperName = ucfirst($cacherName).'SetMapper';
            //FIXME
        }
        return true;
    }


    /**
     * @return string userPwd
     */
    public function getUserPwd()
    {
        return $this->userPwd;
    }




}