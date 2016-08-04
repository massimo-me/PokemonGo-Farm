<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Controller;

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

        $controllers->get('/stop/{configName}', function($configName) {
            return call_user_func([$this, 'stopAction'], $configName);
        })->bind('bot_stop');

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
        $config = $this->getConfig($configName);

        return $this->getApp()['twig']->render('bot/show.html.twig', [
            'config' => $config,
            'isRunning' => SilexApp::getInstance()['bot.process']->isRunning($config)
        ]);
    }

    /**
     * @param $configName
     * @return Response
     */
    public function startAction($configName)
    {
        $config = $this->getConfig($configName);

        $botProcess = SilexApp::getInstance()['bot.process'];

        if (!$botProcess->isRunning($config)) {
            $botProcess->start($config);
        }

        return SilexApp::getInstance()->redirect(
            SilexApp::getInstance()['url_generator']->generate('bot_show', [
                'configName' => $configName
            ])
        );
    }

    /**
     * @param $configName
     * @return Response
     */
    public function stopAction($configName)
    {
        $config = $this->getConfig($configName);

        $botProcess = SilexApp::getInstance()['bot.process'];

        $botProcess->kill($config);

        return SilexApp::getInstance()->redirect(
            SilexApp::getInstance()['url_generator']->generate('bot_show', [
                'configName' => $configName
            ])
        );
    }

    /**
     * @param $configName
     * @param int $maxLogLines
     * @return JsonResponse
     */
    public function poolAction($configName, $maxLogLines = 20)
    {
        $config = $this->getConfig($configName);

        $botProcess = SilexApp::getInstance()['bot.process'];

        $isRunning = $botProcess->isRunning($config);
        $logFilePath = $botProcess->getLogFilePath($config);

        if (!file_exists($logFilePath)) {
            return new JsonResponse([
                'content' => '',
                'status' => 'not-started'
            ]);
        }

        $logLines = explode("\n", file_get_contents($logFilePath));

        return new JsonResponse([
            'content' => implode("\n", array_slice(array_reverse($logLines), 0, $maxLogLines)),
            'status' => ($isRunning) ? 'running' : 'pending'
        ]);
    }
}
