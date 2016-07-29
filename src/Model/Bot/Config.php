<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Model\Bot;

/**
 * Class Config
 * @package ChiarilloMassimo\PokemonGo\Farm\Model\Bot
 * @see https://github.com/PokemonGoF/PokemonGo-Bot/wiki/Configuration-files
 */
class Config
{
    const EXTENSION = 'json';

    const AUTH_GOOGLE = 'google';
    const AUTH_PTC = 'ptc';

    const MODE_ALL = 'all';
    const MODE_POKE = 'poke';
    const MODEL_FARM = 'farm';

    const UNIT_KM = 'km';
    const UNIT_MI = 'mi';
    const UNIT_FT = 'ft';

    protected $authService = self::AUTH_GOOGLE;
    protected $username;
    protected $password;
    protected $location;
    protected $gmapKey;
    protected $maxSteps = 5;
    protected $mode = self::MODE_ALL;
    protected $walk = 4.16;
    protected $debug = false;
    protected $test = false;
    protected $initialTransfer = 0;
    protected $locationCache = true;
    protected $distanceUnit = self::UNIT_KM;
    protected $itemFilter = [];
    protected $evolveAll = false;
    protected $evolveSpeed = 20;
    protected $cpMin = 300;
    protected $useLuckyEgg = false;
    protected $evolveCaptured = false;

    /**
     * @return mixed
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * @param mixed $authService
     * @return $this
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGmapKey()
    {
        return $this->gmapKey;
    }

    /**
     * @param mixed $gmapKey
     * @return $this
     */
    public function setGmapKey($gmapKey)
    {
        $this->gmapKey = $gmapKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxSteps()
    {
        return $this->maxSteps;
    }

    /**
     * @param int $maxSteps
     * @return $this
     */
    public function setMaxSteps($maxSteps)
    {
        $this->maxSteps = $maxSteps;

        return $this;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return float
     */
    public function getWalk()
    {
        return $this->walk;
    }

    /**
     * @param float $walk
     * @return $this
     */
    public function setWalk($walk)
    {
        $this->walk = $walk;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param boolean $test
     * @return $this
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getInitialTransfer()
    {
        return $this->initialTransfer;
    }

    /**
     * @param boolean $initialTransfer
     * @return $this
     */
    public function setInitialTransfer($initialTransfer)
    {
        $this->initialTransfer = $initialTransfer;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getLocationCache()
    {
        return $this->locationCache;
    }

    /**
     * @param boolean $locationCache
     * @return $this
     */
    public function setLocationCache($locationCache)
    {
        $this->locationCache = $locationCache;

        return $this;
    }

    /**
     * @return string
     */
    public function getDistanceUnit()
    {
        return $this->distanceUnit;
    }

    /**
     * @param string $distanceUnit
     * @return $this
     */
    public function setDistanceUnit($distanceUnit)
    {
        $this->distanceUnit = $distanceUnit;

        return $this;
    }

    /**
     * @return array
     */
    public function getItemFilter()
    {
        return $this->itemFilter;
    }

    /**
     * @param array $itemFilter
     * @return $this
     */
    public function setItemFilter($itemFilter)
    {
        $this->itemFilter = $itemFilter;

        return $this;
    }

    /**
     * @return array
     */
    public function getEvolveAll()
    {
        return $this->evolveAll;
    }

    /**
     * @param array $evolveAll
     * @return $this
     */
    public function setEvolveAll($evolveAll)
    {
        $this->evolveAll = $evolveAll;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getEvolveSpeed()
    {
        return $this->evolveSpeed;
    }

    /**
     * @param boolean $evolveSpeed
     * @return $this
     */
    public function setEvolveSpeed($evolveSpeed)
    {
        $this->evolveSpeed = $evolveSpeed;

        return $this;
    }

    /**
     * @return int
     */
    public function getCpMin()
    {
        return $this->cpMin;
    }

    /**
     * @param int $cpMin
     * @return $this
     */
    public function setCpMin($cpMin)
    {
        $this->cpMin = $cpMin;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getUseLuckyEgg()
    {
        return $this->useLuckyEgg;
    }

    /**
     * @param boolean $useLuckyEgg
     * @return $this
     */
    public function setUseLuckyEgg($useLuckyEgg)
    {
        $this->useLuckyEgg = $useLuckyEgg;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getEvolveCaptured()
    {
        return $this->evolveCaptured;
    }

    /**
     * @param boolean $evolveCaptured
     * @return $this
     */
    public function setEvolveCaptured($evolveCaptured)
    {
        $this->evolveCaptured = $evolveCaptured;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return sprintf('config_%s.%s', $this->getUsername(), self::EXTENSION);
    }
}
