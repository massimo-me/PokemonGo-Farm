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

        $this->clearLog($config);

        $command = sprintf(
            'pip install -r requirements.txt && ./pokecli.py -cf %s > %s',
            $configPath,
            $this->getLogFilePath($config)
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
    public function kill(Config $config)
    {
        $configPath = SilexApp::getInstance()['bot.config_manager']->getPath($config);

        $this->clearLog($config);

        $this->run(
            sprintf(
                'kill $(ps aux | grep \'python ./pokecli.py -cf %s\' | awk \'{print $2}\')',
                $configPath
            ),
            false
        );
    }

    /**
     * @param Config $config
     * @return int|null
     */
    public function isRunning(Config $config)
    {
        $configPath = SilexApp::getInstance()['bot.config_manager']->getPath($config);

        $result = $this->run(
            sprintf(
                'ps aux | grep \'python ./pokecli.py -cf %s\' | awk \'{print $2}\'',
                $configPath
            ),
            false
        );

        return count(array_filter(explode("\n", $result))) > 2;
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
     * @param Config $config
     */
    public function clearLog(Config $config)
    {
        $logFilePath = $this->getLogFilePath($config);

        if (file_exists($logFilePath)) {
            unlink($logFilePath);
        }
    }

    /**
     * @param $command
     * @param bool $background
     * @return int|null
     */
    protected function run($command, $background = true)
    {
        $process = new Process($command);

        if ($background) {
            $process->start();
            return $process->getPid();
        }

        try {
            $process->run();
            return $process->getOutput();
        } catch (\RuntimeException $e) {
            return;
        }
    }
}
