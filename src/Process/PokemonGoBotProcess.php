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

        $command = sprintf(
            'pip install -r requirements.txt && ./pokecli.py -cf %s > %s',
            $configPath,
            sprintf('%s/%s.log', SilexApp::getInstance()['app.logs.dir'], $config->getUsername())
        );

        if ($this->virtualEnv) {
            $command = 'virtualenv . && source bin/activate && ' . $command;
        }

        $process = new Process(
            sprintf(
                '(cd %s/../PokemonGo-Bot %s) &',
                SilexApp::getInstance()['app.dir'],
                $command
            )
        );

        $process->start();

        //Wtf?? @todo fix
        return $process->getPid() + 1 ;
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