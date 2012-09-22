<?php

class Settings
{
    /**
     * Singletone instance
     */
    private static $_instance;

    /**
     * Settings array
     */
    protected $_settings;

    /**
     * Load Settings
     *
     * Loads the application configuration file from the specified file.
     *
     * @param  string $filename Name of settings file
     * @return void
     */
    public function load($filename)
    {
        //$configIni = new Config_Ini();
        //$this->_settings = $configIni->load($filename);

        $this->_settings = array();
        return $this->_settings;
    }

    /**
     * Instance accessor
     *
     * Returns the singletone instance of the class
     *
     * @return Settings object
     */
    public static function getInstance()
    {
        if(! isset(self::$_instance)) {
            self::$_instance = new Settings();
        }

        return self::$_instance;
    }

    /**
     * Setting accessor
     *
     * Retrieves and returns a specific setting based on the key passed.
     *
     * @param  string $setting The key to the setting
     * @return mixed 
     */
    public function __get($setting)
    {
        if(array_key_exists($setting, $this->_settings)) {
            return $this->_settings[$setting];
        }
        else {
            foreach($this->_settings as $section) {
                if(array_key_exists($setting, $section)) {
                    return $section[$setting];
                }
            }
        }
    }
}
