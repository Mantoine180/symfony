<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Intervenant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('specialite')
            ->add('nbHours')
            ->add('intervenant', EntityType::class, [
                // looks for choices from this entity
                'class' => Intervenant::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'user.firstname',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}
