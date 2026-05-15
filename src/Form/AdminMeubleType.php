<?php

namespace App\Form\Admin;

use App\Entity\Categorie;
use App\Entity\Meuble;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminMeubleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du meuble',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'Ex: Canapé 3 places'],
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug (URL)',
                'attr'  => ['class' => 'form-control', 'placeholder' => 'canape-3-places'],
            ])
            ->add('description', TextareaType::class, [
                'label'    => 'Description',
                'required' => false,
                'attr'     => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (TND)',
                'scale' => 2,
                'attr'  => ['class' => 'form-control', 'placeholder' => '0.00'],
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock',
                'attr'  => ['class' => 'form-control', 'placeholder' => '0'],
            ])
            ->add('image', UrlType::class, [
                'label'    => 'URL de l\'image',
                'required' => false,
                'attr'     => ['class' => 'form-control', 'placeholder' => 'https://...'],
            ])
            ->add('categorie', EntityType::class, [
                'class'        => Categorie::class,
                'choice_label' => 'libelle',
                'label'        => 'Catégorie',
                'attr'         => ['class' => 'form-select'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meuble::class,
        ]);
    }
}