<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Controller;

use ChiarilloMassimo\PokemonGo\Farm\Form\Type\ConfigType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
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

        $controllers->get('/new', function(Request $request) {
           return call_user_func([$this, 'newAction'], $request);
        });

        return $controllers;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        $form = $this->getApp()['form.factory']->create(ConfigType::class);
        return $this->getApp()['twig']->render('bot/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
