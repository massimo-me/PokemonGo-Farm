<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Controller;

use ChiarilloMassimo\PokemonGo\Farm\Model\Bot\Config;
use ChiarilloMassimo\PokemonGo\Farm\Process\PokemonGoBotProcess;
use ChiarilloMassimo\PokemonGo\Farm\SilexApp;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BotController
 * @package ChiarilloMassimo\PokemonGo\Farm\Controller
 */
class BotController extends BaseController
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = parent::connect($app);

        $controllers->get('/start/{configName}', function($configName) {
            return call_user_func([$this, 'startAction'], $configName);
        })->bind('bot_start');

        return $controllers;
    }

    /**
     * @param $configName
     * @return Response
     */
    public function startAction($configName)
    {
        $config = $this->getConfig($configName);

        $pid = (new PokemonGoBotProcess())
            ->start($config);

        SilexApp::getInstance()['session']->set($config->getName(), $pid);

        return new Response($pid);
    }

    /**
     * @param Config $config
     */
    public function poolAction(Config $config)
    {

    }
}
