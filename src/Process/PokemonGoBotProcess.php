<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Process;

use ChiarilloMassimo\PokemonGo\Farm\Model\Bot\Config;
use ChiarilloMassimo\PokemonGo\Farm\SilexApp;
use Symfony\Component\Process\Process;

/**
 * Class PokemonGoBotProcess
 * @package ChiarilloMassimo\PokemonGo\Farm\Process
 */
class PokemonGoBotProcess
{
    /**
     * @var $virtualEnv
     */
    protected $virtualEnv;

    /**
     * PokemonGoBotProcess constructor.
     */
    public function __construct()
    {
        $this->virtualEnv = SilexApp::getInstance()['bot.virtual_env'];
    }

    /**
     * @param Config $config
     * @return int|null
     */
    public function start(Config $config)
    {
        $configPath = SilexApp::getInstance()['bot.config_manager']->getPath($config);

        $logFilePath = sprintf('%s/%s.log', SilexApp::getInstance()['app.logs.dir'], $config->getUsername());

        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }

        $command = sprintf(
            'pip install -r requirements.txt && ./pokecli.py -cf %s > %s',
            $configPath,
            sprintf('%s/%s.log', SilexApp::getInstance()['app.logs.dir'], $config->getUsername())
        );

        if ($this->virtualEnv) {
            $command = 'virtualenv . && source bin/activate && ' . $command;
        }

        return $this->run(
            sprintf(
                '(cd %s/../PokemonGo-Bot %s) &',
                SilexApp::getInstance()['app.dir'],
                $command
            )
        );
    }

    /**
     * @param Config $config
     * @return int|null
     */
    protected function kill(Config $config)
    {
        $configPath = SilexApp::getInstance()['bot.config_manager']->getPath($config);

        return $this->run(
            sprintf(
                'kill $(ps aux | grep \'python ./pokecli.py -cf %s\' | awk \'{print $2}\')',
                $configPath
            )
        );
    }

    /**
     * @param Config $config
     * @return int|null
     */
    public function isRunning(Config $config)
    {
        $configPath = SilexApp::getInstance()['bot.config_manager']->getPath($config);

        $process = new Process(
            sprintf(
                'ps aux | grep \'python ./pokecli.py -cf %s\' | awk \'{print $2}\'',
                $configPath
            )
        );

        $process->run();

        return count(array_filter(explode("\n", $process->getOutput()))) > 2;
    }

    /**
     * @param Config $config
     * @return string
     */
    public function getLogFilePath(Config $config)
    {
        return sprintf('%s/%s.log', SilexApp::getInstance()['app.logs.dir'], $config->getUsername());
    }

    /**
     * @param $command
     * @return int|null
     */
    protected function run($command)
    {
        $process = new Process($command);
        $process->start();

        return $process->getPid();
    }
}
