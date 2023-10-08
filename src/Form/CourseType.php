<?php

namespace App\Form;

use App\Entity\Course;
use App\Enumeration\CourseColor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{EnumType, SubmitType, TextType};

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom*"
            ])
            ->add('room', TextType::class, [
                'label' => 'Salle'
            ])
            ->add('professor', TextType::class, [
                'label' => "Professeur"
            ])
            ->add('color', EnumType::class, [
                'label' => "Couleur",
                'class' => CourseColor::class
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Créer"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class
        ]);
    }
}
