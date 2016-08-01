<?php

namespace ChiarilloMassimo\PokemonGo\Farm;

use ChiarilloMassimo\PokemonGo\Farm\Controller\BotController;
use ChiarilloMassimo\PokemonGo\Farm\Controller\ConfigController;
use ChiarilloMassimo\PokemonGo\Farm\Controller\DashboardController;
use ChiarilloMassimo\PokemonGo\Farm\Process\PokemonGoBotProcess;
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
        array_map(
            function($method) use (&$app) {
                return call_user_func($method, $app);
            },
            [
                [$this, 'configureCustomParameters'],
                [$this, 'registerConfigurations'],
                [$this, 'registerConfigurations'],
                [$this, 'registerSession'],
                [$this, 'registerControllers'],
                [$this, 'registerTwig'],
                [$this, 'registerForm'],
                [$this, 'registerTranslation'],
                [$this, 'registerUrlGenerator'],
                [$this, 'registerPokemonGoBotConfigManager'],
                [$this, 'registerPokemonGoBotProcess'],

            ]
        );

        $this->app = $app;

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     */
    protected function configureCustomParameters(Application $app)
    {
        $app['app.dir'] = $this->getAppDir();
        $app['app.logs.dir'] = $this->getLogsDir();
        $app['app.data.dir'] = $this->getDataDir();

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     */
    protected function registerConfigurations(Application $app)
    {
        $app->register(
            new ConfigServiceProvider(
                sprintf('%s/config/config.json', $this->getAppDir()),
                $this->getParameters()
            )
        );

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     */
    protected function registerControllers(Application $app)
    {
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

    /**
     * @param Application $app
     * @return Application
     */
    protected function registerSession(Application $app)
    {
        $app->register(new SessionServiceProvider());

        return $app;
    }

    /**
     * @param $app
     * @return Application
     */
    protected function registerTwig(Application $app)
    {
        $app->register(new TwigServiceProvider(), [
            'twig.path' => sprintf('%s/../src/Resources/views', $app['app.dir'])
        ]);

        $gmapBrowserApiKey = $app['gmap.browser.api_key'];

        $app->extend(
            'twig',
            function($twig) use ($gmapBrowserApiKey) {
                $twig->addGlobal('gmap_api_key', $gmapBrowserApiKey);

                return $twig;
            }
        );
    }

    /**
     * @param Application $app
     * @return Application
     */
    protected function registerForm(Application $app)
    {
        $app->register(new FormServiceProvider());

        return $app;
    }

    /**
     * @param  $app
     * @return Application
     */
    protected function registerTranslation($app)
    {
        $app->register(new TranslationServiceProvider());

        $app->extend(
            'translator',
            function($translator) use ($app) {
                $translator->addLoader('json', new JsonFileLoader());
                $translator->addResource('json', sprintf('%s/%s', $app['app.dir'], 'locales/en.json'), 'en');

                return $translator;
            }
        );
    }

    /**
     * @param Application $app
     * @return Application
     */
    protected function registerUrlGenerator(Application $app)
    {
        $app->register(new UrlGeneratorServiceProvider());

        return $app;
    }

    /**
     * @param $app
     * @return Application
     */
    protected function registerPokemonGoBotConfigManager(Application $app)
    {
        $app['bot.config_manager'] = $app->share(
            function() {
                return new ConfigManager();
            }
        );

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     */
    protected function registerPokemonGoBotProcess(Application $app)
    {
        $app['bot.process'] = $app->share(
            function() {
                return new PokemonGoBotProcess();
            }
        );

        return $app;
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
