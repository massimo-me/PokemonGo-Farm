<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Controller;

use ChiarilloMassimo\PokemonGo\Farm\Process\PokemonGoBotProcess;
use ChiarilloMassimo\PokemonGo\Farm\SilexApp;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        //@ToDo Refactor this shit :)
        $controllers->get('/start/{configName}', function($configName) {
            return call_user_func([$this, 'startAction'], $configName);
        })->bind('bot_start');

        $controllers->get('/show/{configName}', function($configName) {
            return call_user_func([$this, 'showAction'], $configName);
        })->bind('bot_show');

        $controllers->get('/pool/{configName}', function($configName) {
            return call_user_func([$this, 'poolAction'], $configName);
        })->bind('bot_pool');

        return $controllers;
    }

    /**
     * @param $configName
     * @return mixed
     */
    public function showAction($configName)
    {
        return $this->getApp()['twig']->render('bot/show.html.twig', [
            'config' => $this->getConfig($configName)
        ]);
    }

    /**
     * @param $configName
     * @return Response
     */
    public function startAction($configName)
    {
        $config = $this->getConfig($configName);

        (new PokemonGoBotProcess())
            ->start($config);

        return SilexApp::getInstance()->redirect(
            SilexApp::getInstance()['url_generator']->generate('bot_show', [
                'configName' => $configName
            ])
        );
    }

    /**
     * @param $configName
     * @param int $logLines
     * @return JsonResponse
     */
    public function poolAction($configName, $logLines = 20)
    {
        $config = $this->getConfig($configName);

        $isRunning = (new PokemonGoBotProcess())
            ->isRunning($config);

        $logFilePath = sprintf('%s/%s.log', SilexApp::getInstance()['app.logs.dir'], $config->getUsername());

        if (!file_exists($logFilePath)) {
            return new JsonResponse([
                'content' => '',
                'status' => 'not-started'
            ]);
        }

        $lines = explode(
            "\n",
            file_get_contents(
                sprintf('%s/%s.log', SilexApp::getInstance()['app.logs.dir'], $config->getUsername()
                )
            )
        );

        return new JsonResponse([
            'content' => implode("\n", array_slice(array_reverse($lines), 0, $logLines)),
            'status' => ($isRunning) ? 'running' : 'pending'
        ]);
    }
}
