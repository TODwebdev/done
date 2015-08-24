<?php
namespace Config;

/**
 * We need to check if valid file config file name is provided
 * and return Config object
 */
class StandardConfigFactory {

    protected $confFile;

    /**
     * Accept full path to a file with config parameters
     * @param string $configFilePath
     */
    public function __construct($configFilePath)
    {
        if (is_null($configFilePath)
            || !is_string($configFilePath)
            || empty($configFilePath)
        ) {
            throw new \InvalidArgumentException(sprintf('Need valid config file name. %s is invalid', $configFilePath));
        }

        $this->confFile = $configFilePath;
    }

    /**
     * @param string $pwd user identifier
     * @return UserConfig
     */
    public function build($pwd)
    {
        try {
            if (!$this->isValid()) {
                throw new \InvalidArgumentException(sprintf('Need valid config file name. %s is invalid', $this->confFile));
            }

            $config = new UserConfig($pwd, $this->confFile);
            $config->init();

            return $config;
        } catch (\Exception $e) {
            throw new \RuntimeException(sprintf('Config was unable to initialize: %s', $e->getMessage()));
        }
    }

    protected function isValid()
    {
        if (is_file($this->confFile)) {
            return true;
        }
        return false;
    }
}