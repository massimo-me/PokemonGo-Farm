<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Controller;

use ChiarilloMassimo\PokemonGo\Farm\SilexApp;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;

/**
 * Class BaseController
 * @package ChiarilloMassimo\PokemonGo\Farm\Controller
 */
class BaseController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return ControllerCollection
     */
    public function connect(Application $app)
    {
        return $app['controllers_factory'];
    }

    /**
     * @return Application
     */
    public function getApp()
    {
        return SilexApp::getInstance();
    }
}
