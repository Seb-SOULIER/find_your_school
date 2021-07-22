<?php

namespace App\Form;

use App\Entity\Cursus;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture')
            ->add('lastname')
            ->add('firstname')
            ->add('picture')
//            ->add('school_id')
//            ->add('school_name')
            ->add('cursus',EntityType::class, [
                'class' => Cursus::class,
                'choice_label' => 'name'
            ])
            ->add('city');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
