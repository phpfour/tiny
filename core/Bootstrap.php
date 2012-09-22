<?php

/**
 * Bootstrap
 *
 * Framework process control file, loaded by the front controller
 *
 * @package     Core
 * @author      Md Emran Hasan
 * @copyright   (c) 2009 Right Brain Solution Ltd.
 * @license     http://framework.rightbrainsolution.com
 */

class Bootstrap
{
    /**
     * The loaded configuration settings
     *
     * @var array
     */
    public static $_settings;

    /**
     * Current environment
     *
     * @var string
     */
    public static $_environment;

    /**
     * Holds the path to the configuration file
     *
     * @var string
     */
    public static $_configurationFilePath;

    /**
     * Initialize application.
     *
     * Invokes the private functions to load configuration from file, setup
     * environment configuration, and preapre the framework-related compontents.
     *
     * @param  string $environment Environment name from config file
     * @return void
     */
    public static function initialize($environment)
    {
        self::_loadConfiguration($environment);
        self::_setupEnviroment();
        self::_prepare();
    }

    /**
     * Load configuration
     *
     * It first invokes the private functions to setup the base paths and add the
     * overrides. Afterwards, it loads the configuration file from the app/configs
     * folder.
     *
     * @param  string $environment
     * @return void
     */
    private static function _loadConfiguration($environment)
    {
        self::_setupPaths();
        self::_setupOverrides();

        $settings = Settings::getInstance();
        $settings->load(APPPATH . "config/application.ini");

        self::$_environment = $environment;
        self::$_settings = $settings->$environment;
    }

    /**
     * Setup Paths
     *
     * Identify the absolute path of the core and application folder and then
     * define the system-wide constants. Update the include path with the path of
     * libraries and models folder.
     *
     * @return void
     */
    private static function _setupPaths()
    {
        // First get the paths
        $corePath = realpath(dirname(__FILE__));
        $appPath = str_replace("core", "app", $corePath);

        // Set system-wide path constants
        define ('DIRSEP',   DIRECTORY_SEPARATOR);
        define ('BASEPATH', $corePath . DIRSEP);
        define ('APPPATH',  $appPath . DIRSEP);

        // Prepare the autoload path array
        $autoloadPath[] = get_include_path();

        $autoloadPath[] = BASEPATH . 'libraries' . DIRSEP;
        $autoloadPath[] = APPPATH  . 'models'    . DIRSEP;
        $autoloadPath[] = APPPATH  . 'libraries' . DIRSEP;

        // Update include path
        set_include_path(implode(PATH_SEPARATOR, $autoloadPath));
    }

    /**
     * Setup Overrides
     *
     * Sets the autoload and output buffering overrides.
     *
     * @return void
     */
    private static function _setupOverrides()
    {
        include BASEPATH . "libraries/Loader.php";
        include BASEPATH . "libraries/Output.php";

        spl_autoload_register(array("Loader", "autoload"));
        ob_start(array("Output", 'outputBuffer'));
        register_shutdown_function(array("Output", 'shutdown'));

        Output::initialize();
    }

    /**
     * Setup Environment
     *
     * Sets the error reporting level and the various PHP specific settings.
     *
     * @return void
     */
    private static function _setupEnviroment()
    {
        error_reporting(E_ALL | E_STRICT);

        //$phpSettings = self::$_settings['php'];

        // Set display error
        //ini_set('display_startup_errors', $phpSettings['display_startup_errors']);
        //ini_set('display_errors', $phpSettings['display_errors']);

        // Set timezone
        //date_default_timezone_set($phpSettings['default_timezone']);
        date_default_timezone_set("Asia/Dhaka");
    }

    /**
     * Prepare framework
     *
     * Loads the router and sets the controllers folder. Then delegates for next
     * actions.
     *
     * @return void
     */
    private static function _prepare()
    {
        Registry::set('settings', self::$_settings);
        Registry::set('environment', self::$_environment);

        $router = new Router();
        $router->setPath(APPPATH . 'controllers');
        $router->delegate();
    }
}
