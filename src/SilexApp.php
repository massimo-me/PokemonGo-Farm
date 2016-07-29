<?php

namespace ChiarilloMassimo\PokemonGo\Farm;

use ChiarilloMassimo\PokemonGo\Farm\Controller\BotController;
use ChiarilloMassimo\PokemonGo\Farm\Controller\ConfigController;
use ChiarilloMassimo\PokemonGo\Farm\Controller\DashboardController;
use ChiarilloMassimo\PokemonGo\Farm\Service\ConfigManager;
use Igorw\Silex\ConfigServiceProvider;
use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\Translation\Loader\JsonFileLoader;

/**
 * Class App
 * @package Skuola\Drm
 */
class SilexApp
{
    /**
     * @var null|Application
     */
    private static $instance;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @return Application
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = (new static())
                ->configure(new Application());
        }

        return static::$instance;
    }

    /**
     * @param Application $app
     * @return Application
     */
    protected function configure(Application $app)
    {
        $this->app = $app;

        $this->configureCustomParameters();
        $this->registerConfigurations();
        $this->registerSession();
        $this->registerControllers();
        $this->registerTwig();
        $this->registerForm();
        $this->registerTranslation();
        $this->registerUrlGenerator();
        $this->registerPokemonGoBotConfigManager();

        return $app;
    }

    protected function configureCustomParameters()
    {
        $this->app['app.dir'] = $this->getAppDir();
        $this->app['app.logs.dir'] = $this->getLogsDir();
        $this->app['app.data.dir'] = $this->getDataDir();
    }

    protected function registerConfigurations()
    {
        $this->app->register(
            new ConfigServiceProvider(
                sprintf('%s/config/config.json', $this->getAppDir()),
                $this->getParameters()
            )
        );
    }

    /**
     * @return Application
     */
    protected function registerControllers()
    {
        $app = $this->app;

        array_map(
            function($controllerProvider) use ($app) {
                $app->mount($controllerProvider['prefix'], $controllerProvider['class']);
            },[
                [
                    'prefix' => '/',
                    'class' => new DashboardController()
                ],
                [
                    'prefix' => '/config',
                    'class' => new ConfigController()
                ],
                [
                    'prefix' => '/bot',
                    'class' => new BotController()
                ]
            ]
        );

        return $app;
    }

    protected function registerSession()
    {
        $this->app->register(new SessionServiceProvider());
    }

    protected function registerTwig()
    {
        $this->app->register(new TwigServiceProvider(), [
            'twig.path' => sprintf('%s/../src/Resources/views', $this->app['app.dir'])
        ]);

        $gmapBrowserApiKey = $this->app['gmap.browser.api_key'];

        $this->app->extend('twig', function($twig) use ($gmapBrowserApiKey) {
            $twig->addGlobal('gmap_api_key', $gmapBrowserApiKey);

            return $twig;
        });
    }

    protected function registerForm()
    {
        $this->app->register(new FormServiceProvider());
    }

    protected function registerTranslation()
    {
        $this->app->register(new TranslationServiceProvider());
        $appDir = $this->app['app.dir'];

        $this->app->extend('translator', function($translator) use ($appDir) {
            $translator->addLoader('json', new JsonFileLoader());

            $translator->addResource('json', sprintf('%s/%s', $appDir, 'locales/en.json'), 'en');

            return $translator;
        });
    }

    protected function registerUrlGenerator()
    {
        $this->app->register(new UrlGeneratorServiceProvider());
    }

    protected function registerPokemonGoBotConfigManager()
    {
        $this->app['bot.config_manager'] = $this->app->share(function() {
           return new ConfigManager();
        });
    }

    /**
     * @return string
     */
    protected function getLogsDir()
    {
        $logsDir = sprintf('%s/../app/logs', __DIR__);

        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0775, true);
        }

        return realpath($logsDir);
    }

    /**
     * @return string
     */
    protected function getDataDir()
    {
        return realpath(sprintf('%s/../app/data', __DIR__));
    }

    /**
     * @return string
     */
    protected function getAppDir()
    {
        return realpath(sprintf('%s/../app/', __DIR__));
    }

    /**
     * @return mixed
     */
    protected function getParameters()
    {
        return json_decode(
            file_get_contents(
                sprintf('%s/../app/config/parameters.json', __DIR__)
            ),
            true
        ) + [
            'app.dir' => $this->app['app.dir'],
            'app.logs.dir' => $this->app['app.logs.dir']
        ];
    }

    /**
     * SilexApp constructor.
     */
    private function __construct()
    {
        return $this;
    }
}
