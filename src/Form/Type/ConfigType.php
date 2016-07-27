<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Form\Type;

use ChiarilloMassimo\PokemonGo\Farm\Bot;
use ChiarilloMassimo\PokemonGo\Farm\Model\Bot\Config;
use ChiarilloMassimo\PokemonGo\Farm\SilexApp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ConfigType
 * @package ChiarilloMassimo\PokemonGo\Farm\Form\Type
 */
class ConfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authService', ChoiceType::class, [
                'label' => 'bot.new.auth_service',
                'choices' => [
                    'Google' => Config::AUTH_GOOGLE,
                    'Pokemon Trainer Club' => Config::AUTH_PTC
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'bot.new.username'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'bot.new.password'
            ])
            ->add('gmapKey', TextType::class, [
                'label' => 'bot.new.gmap_key'
            ])
            ->add('maxSteps', NumberType::class, [
                'label' => 'bot.new.max_steps',
                'data' => 5
            ])
            ->add('location', TextType::class, [
                'label' => 'bot.new.location',
                'data' => 'Central Park West, New York'
            ])
            ->add('locationCache', ChoiceType::class, [
                'label' => 'bot.new.location_cache',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('mode', ChoiceType::class, [
                'label' => 'bot.new.mode',
                'choices' => [
                    'All' => Config::MODE_ALL,
                    'Pokémon' => Config::MODE_POKE,
                    'PokéStop' => Config::MODEL_FARM
                ]
            ])
            ->add('walk', NumberType::class, [
                'label' => 'bot.new.walk',
                'data' => 4.16
            ])
            ->add('distanceUnit', ChoiceType::class, [
                'label' => 'bot.new.distance_unit',
                'choices' => [
                    'Kilometers' => Config::UNIT_KM,
                    'Miles' => Config::UNIT_MI,
                    'Feet' => Config::UNIT_FT
                ]
            ])
            ->add('initialTransfer', NumberType::class, [
                'label' => 'bot.new.initial_tranfer',
                'data' => 0
            ])
            ->add('cpMin', NumberType::class, [
                'label' => 'bot.new.cp_min',
                'data' => 0,
            ])
            ->add('evolveSpeed', NumberType::class, [
                'label' => 'bot.new.evolve_speed',
                'data' => 20
            ])
            ->add('evolveCaptured', ChoiceType::class, [
                'label' => 'bot.new.evolve_captured',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('evolveAll', ChoiceType::class, [
                'label' => 'bot.new.evolve_all',
                'choices' => array_combine(
                    array_values(Bot::$pokemons),
                    array_values(Bot::$pokemons)
                ),
                'multiple' => true,
                'attr' => [
                    'multiple' => true
                ],
                'required' => false
            ])
            ->add('useLuckyEgg', ChoiceType::class, [
                'label' => 'bot.new.use_lucky_egg',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('itemFilter', ChoiceType::class, [
                'label' => 'bot.new.item_filter',
                'choices' => array_flip(Bot::$items),
                'multiple' => true,
                'attr' => [
                    'multiple' => true
                ],
                'required' => false
            ])
            ->add('debug', ChoiceType::class, [
                'label' => 'bot.new.debug',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('test', ChoiceType::class, [
                'label' => 'bot.new.test',
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>  Config::class,
            'gmapKey' => SilexApp::getInstance()['gmap.server.api_key']
        ]);
    }
}
