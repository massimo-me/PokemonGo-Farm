<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Controller;

use ChiarilloMassimo\PokemonGo\Farm\Form\Type\ConfigType;
use ChiarilloMassimo\PokemonGo\Farm\Process\PokemonGoBotProcess;
use ChiarilloMassimo\PokemonGo\Farm\Service\ConfigManager;
use ChiarilloMassimo\PokemonGo\Farm\SilexApp;
use Silex\Application;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ConfigController
 * @package ChiarilloMassimo\PokemonGo\Farm\Controller
 */
class ConfigController extends BaseController
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $controllers = parent::connect($app);

        $controllers->get('/new', function() {
           return call_user_func([$this, 'newAction']);
        })->bind('config_new');

        $controllers->get('/list', function() {
            return call_user_func([$this, 'listAction']);
        })->bind('config_list');

        $controllers->get('/edit/{configName}', function($configName) {
            return call_user_func([$this, 'editAction'], $configName);
        })->bind('config_edit');

        $controllers->get('/remove/{configName}', function($configName) {
            return call_user_func([$this, 'removeAction'], $configName);
        })->bind('config_remove');

        $controllers->post('/build', function(Request $request) {
            return call_user_func([$this, 'buildAction'], $request);
        })->bind('config_build');

        return $controllers;
    }

    /**
     * @return Response
     */
    public function listAction()
    {
        return $this->getApp()['twig']->render('config/list.html.twig', [
            'configs' => SilexApp::getInstance()['bot.config_manager']->findAll()
        ]);
    }

    /**
     * @return Response
     */
    public function newAction()
    {
        $form = $this->getApp()['form.factory']->create(ConfigType::class);

        return $this->getApp()['twig']->render('config/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $configName
     * @return mixed
     */
    public function editAction($configName)
    {
        $config = $this->getConfig($configName);

        $form = $this->getApp()['form.factory']->create(ConfigType::class, $config);

        return $this->getApp()['twig']->render('config/edit.html.twig', [
            'form' => $form->createView(),
            'config' => $config
        ]);
    }

    /**
     * @param $configName
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($configName)
    {
        $config = $this->getConfig($configName);

        SilexApp::getInstance()['bot.config_manager']->remove($config);
        SilexApp::getInstance()['session']->getFlashBag()->add('success', 'config_remove.removed');

        return SilexApp::getInstance()->redirect(
            SilexApp::getInstance()['url_generator']->generate('config_list')
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function buildAction(Request $request)
    {
        $form = $this->getApp()['form.factory']->create(ConfigType::class);

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->getApp()['twig']->render('config/new.html.twig', ['form' => $form->createView()]);
        }

        $builded = $this->getApp()['bot.config_manager']
            ->build($form->getData());

        if (!$builded) {
            $form->addError(new FormError('config_new.error'));

            return $this->getApp()['twig']->render('config/new.html.twig', ['form' => $form->createView()]);
        }

        SilexApp::getInstance()['session']->getFlashBag()->add('success', 'config_build.builded');

        return SilexApp::getInstance()->redirect(
            SilexApp::getInstance()['url_generator']->generate('config_list')
        );
    }
}
