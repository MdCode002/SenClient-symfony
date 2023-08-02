<?php

namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchTerm', ChoiceType::class, [
                'label' => 'Filtre Statut ',
                'choices' => [
                    'Actif' => 1,
                    'Inactif' => 0,
                ],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'placeholder' => 'Sélectionnez un critère de recherche',
                ],
            ])
            ->add('Filtrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
