<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Service;

use ChiarilloMassimo\PokemonGo\Farm\Model\Bot\Config;
use ChiarilloMassimo\PokemonGo\Farm\SilexApp;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var SplFileInfo[]
     */
    protected $list;

    /**
     * ConfigManager constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();

        $this->list = (new Finder())
            ->in(SilexApp::getInstance()['app.data.dir'])
            ->files('*.'.Config::EXTENSION);
    }

    /**
     * @return Config[]
     */
    public function findAll()
    {
        $configs = [];

        foreach ($this->list as $splFileInfo) {
            $configs[] = $this->jsonToModel($splFileInfo->getContents());
        }

        return $configs;
    }

    /**
     * @param $configName
     * @return Config
     */
    public function find($configName)
    {
        foreach ($this->list as $splFileInfo) {
            if ($splFileInfo->getRelativePathname() == $configName) {
                return $this->jsonToModel(
                    $splFileInfo->getContents()
                );
            }
        }

        return false;
    }

    /**
     * @param Config $config
     * @return int
     */
    public function build(Config $config)
    {
        try {
            $this->filesystem->dumpFile(
                sprintf('%s/%s', SilexApp::getInstance()['app.data.dir'], $config->getName()),
                $this->modelToJson($config)
            );
        } catch (IOException $e) {
            //@ToDo Log exception
            return false;
        }

        return $config;
    }

    /**
     * @param $configName
     * @return bool
     */
    public function read($configName)
    {
        $config = $this->find($configName);

        if (!$config) {
            return false;
        }

        return $config;
    }

    /**
     * @param Config $config
     */
    public function remove(Config $config)
    {
        return $this->filesystem->remove(
            sprintf('%s/%s', SilexApp::getInstance()['app.data.dir'], $config->getName())
        );
    }

    /**
     * @param string $config
     * @return Config
     */
    public function jsonToModel($config)
    {
        $data = new Config();

        foreach (json_decode($config, true) as $key => $value) {
            if (!array_key_exists($key, self::$methodMap)) {
                continue;
            }

            $dataProperty = self::$methodMap[$key];

            if ($key == 'item_filter' || $key == 'evolve_all') {
                if ($value == 'NONE') {
                    $data->{'set'.ucfirst($dataProperty)}([]);

                    continue;
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
    public function modelToJson(Config $config)
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
}
