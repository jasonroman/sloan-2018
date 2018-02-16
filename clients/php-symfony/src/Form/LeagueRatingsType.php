<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeagueRatingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('league', ChoiceType::class, [
                'label'   => 'League: ',
                'choices' => $options['league_choices'],
            ])
            ->add('ratingsType', ChoiceType::class, [
                'label'   => 'Ratings Type: ',
                'choices' => ['Offense' => 'offense', 'Defense' => 'defense', 'Offense and Defense' => 'ratings'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('league_choices');
    }
}