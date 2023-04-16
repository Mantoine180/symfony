<?php

namespace App\Form;

use App\Entity\Calendrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut', DateTimeType::class, [
                'widget' => 'single_text',
                'date_label' => 'Starts On',
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Alternance' => 'alternance',
                    'Cours' => 'cours'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}
