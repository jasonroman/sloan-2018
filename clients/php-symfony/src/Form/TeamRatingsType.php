<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamRatingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team', ChoiceType::class, [
                'label'   => 'Team: ',
                'choices' => $options['team_choices'],
            ])
            ->add('ratingsType', ChoiceType::class, [
                'label'   => 'Ratings Type: ',
                'choices' => ['Offense' => 'offense', 'Defense' => 'defense'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('team_choices');
    }
}