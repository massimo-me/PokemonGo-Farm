<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DashboardController
 * @package ChiarilloMassimo\PokemonGo\Farm\Controller
 */
class DashboardController extends BaseController
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = parent::connect($app);

        $controllers->get('/', function() {
            return call_user_func([$this, 'indexAction']);
        })->bind('dashboard');

        return $controllers;
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->getApp()['twig']->render('index.html.twig');
    }
}
