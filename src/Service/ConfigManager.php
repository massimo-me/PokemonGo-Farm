<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Service;

use ChiarilloMassimo\PokemonGo\Farm\Model\Bot\Config;
use ChiarilloMassimo\PokemonGo\Farm\SilexApp;

/**
 * Class ConfigManager
 * @package ChiarilloMassimo\PokemonGo\Farm\Service
 */
class ConfigManager
{
    /**
     * @param Config $config
     * @return int
     */
    public function create(Config $config)
    {
        $fileName = sprintf('config_%s.json', $config->getUsername());

        return file_put_contents(
            sprintf('%s/%s', SilexApp::getInstance()['app.data.dir'], $fileName),
            json_encode($config->toArray())
        );
    }
}