<?php

namespace ChiarilloMassimo\PokemonGo\Farm\Form\Type;

use ChiarilloMassimo\PokemonGo\Farm\Bot;
use ChiarilloMassimo\PokemonGo\Farm\Model\Bot\Config;
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
                'choices' => [
                    'Google' => Config::AUTH_GOOGLE,
                    'Pokemon Trainer Club' => Config::AUTH_PTC
                ]
            ])
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('gmapKey', TextType::class)
            ->add('maxSteps', NumberType::class, [
                'data' => 5
            ])
            ->add('locationCache', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'All' => Config::MODE_ALL,
                    'Pokémon' => Config::MODE_POKE,
                    'PokéStop' => Config::MODEL_FARM
                ]
            ])
            ->add('walk', NumberType::class, [
                'data' => 4.16
            ])
            ->add('distanceUnit', ChoiceType::class, [
                'choices' => [
                    'Kilometers' => Config::UNIT_KM,
                    'Miles' => Config::UNIT_MI,
                    'Feet' => Config::UNIT_FT
                ]
            ])
            ->add('initialTransfer', NumberType::class, [
                'data' => 0
            ])
            ->add('cpMin', NumberType::class)
            ->add('evolveSpeed', NumberType::class, [
                'data' => 20
            ])
            ->add('evolveCaptured', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('useLuckyEgg', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('evolveAll', ChoiceType::class, [
                'choices' => array_combine(
                    array_values(Bot::$pokemons),
                    array_values(Bot::$pokemons)
                )
                ,
                'multiple' => true,
                'attr' => [
                    'multiple' => true
                ],
                'required' => false
            ])
            ->add('itemFilter', ChoiceType::class, [
                'choices' => array_flip(Bot::$items),
                'multiple' => true,
                'attr' => [
                    'multiple' => true
                ],
                'required' => false
            ])
            ->add('debug', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ]
            ])
            ->add('test', ChoiceType::class, [
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
            'data_class' =>  Config::class
        ]);
    }
}
