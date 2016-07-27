<?php

namespace ChiarilloMassimo\PokemonGo\Farm;

use ChiarilloMassimo\PokemonGo\Farm\Controller\BotController;
use ChiarilloMassimo\PokemonGo\Farm\Controller\DashboardController;
use Igorw\Silex\ConfigServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;

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

        $this->app['app.dir'] = $this->getAppDir();
        $this->app['app.cache.dir'] = $this->getCacheDir();

        $this->registerConfigurations();
        $this->registerControllers();
        $this->registerTwig();

        return $app;
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
                    'prefix' => '/bot',
                    'class' => new BotController()
                ]
            ]
        );

        return $app;
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

    protected function registerTwig()
    {
        $this->app->register(new TwigServiceProvider(), [
            'twig.path' => sprintf('%s/../src/Resources/views', $this->app['app.dir'])
        ]);
    }

    /**
     * @return string
     */
    protected function getCacheDir()
    {
        $cacheDir = sprintf('%s/../app/cache', __DIR__);

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0775, true);
        }

        return realpath($cacheDir);
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
            'app.cache.dir' => $this->app['app.cache.dir']
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
