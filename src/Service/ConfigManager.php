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
     * Config method map
     * @var array
     */
    public static $methodMap = [
        'auth_service' => 'authService',
        'username' => 'username',
        'password' => 'password',
        'location' => 'location',
        'gmapkey' => 'gmapKey',
        'max_steps' => 'maxSteps',
        'mode' => 'mode',
        'walk' => 'walk',
        'debug' => 'debug',
        'test' => 'test',
        'initial_transfer' => 'initialTransfer',
        'location_cache' => 'locationCache',
        'distance_unit' => 'distanceUnit',
        'item_filter' => 'itemFilter',
        'evolve_all' => 'evolveAll',
        'evolve_speed' => 'evolveSpeed',
        'cp_min' => 'cpMin',
        'use_lucky_egg' => 'useLuckyEgg',
        'evolve_captured' => 'evolveCaptured',
    ];

    /**
     * @param array $config
     * @return Config
     */
    public function toConfig(array $config)
    {
        $data = new Config();

        foreach ($config as $key => $value) {
            if (!array_key_exists($key, self::$methodMap)) {
                continue;
            }

            $dataProperty = self::$methodMap[$key];

            if ($key == 'item_filter' || $key == 'evolve_all') {
                if ($value == 'NONE') {
                    $data->{'set'.ucfirst($dataProperty)}([]);
                }

                $data->{'set'.ucfirst($dataProperty)}(explode(',', $value));

                continue;
            }

            $data->{'set'.ucfirst($dataProperty)}($value);
        }

        return $data;
    }

    /**
     * @param Config $config
     * @return string
     */
    public function toJson(Config $config)
    {
        $data = [];

        foreach (self::$methodMap as $key => $property) {
            $value =  $config->{'get'.ucfirst($property)}();

            if ($key == 'item_filter' || $key == 'evolve_all') {
                $data[$key] = (empty($value)) ? 'NONE' : implode(',', $value);

                continue;
            }

            $data[$key] = $value;
        }

        return json_encode($data);
    }


    /**
     * @param Config $config
     * @return int
     */
    public function create(Config $config)
    {
        $fileName = sprintf('config_%s.json', $config->getUsername());

        return file_put_contents(
            sprintf('%s/%s', SilexApp::getInstance()['app.data.dir'], $fileName),
            $this->toJson($config)
        );
    }

    /**
     * @param $configName
     * @return bool
     */
    public function read($configName)
    {
        $configPath = sprintf('%s/%s', SilexApp::getInstance()['app.data.dir'], $configName);

        if (!file_exists($configPath) || pathinfo($configPath, PATHINFO_EXTENSION) != 'json') {
            return false;
        }

        return $this->toConfig(
            json_decode(
                file_get_contents($configPath),
                true
            )
        );
    }
}
