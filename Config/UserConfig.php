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
    public function __construct($pwd)
    {
        parent::__construct();

        if (is_string($pwd)
            && !empty($pwd)
        ) {
            $this->userPwd = $pwd;
        }

        /// Now we should check, if userdata already exists in Redis. If not - write it there
        $redisDataProvider = new RedisDataProvider($this);
        $existsMapper = new RedisExistsMapper($this->userPwd.'_'.'init');
        $val = $redisDataProvider->exists($existsMapper);
        if (1 !== intval($val)) {
            /// write client data

        }
    }


}