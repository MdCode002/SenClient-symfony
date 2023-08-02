<?php

namespace App\Form;

use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recherche', TextType::class, ['attr' => ['placeholder' => 'Mots a chercher ']])
           ->add('searchTerm', ChoiceType::class, [
                'label' => 'Rechercher par ',
                'choices' => [
                    'Nom' => 'nom',
                    'Adresse' => 'address',
                    'Téléphone' => 'tel',
                ],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'placeholder' => 'Sélectionnez un critère de recherche',
                ],
            ])
            ->add('Rechercher', SubmitType::class)
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
