<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('moyenPaiement', ChoiceType::class, [
                'label'   => 'Moyen de paiement',
                'choices' => [
                    'Carte Bancaire (Poste Tunisienne)' => 'poste',
                    'Carte BIAT'                        => 'biat',
                    'Carte STB'                         => 'stb',
                    'Carte Visa/Mastercard'             => 'visa',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir un moyen de paiement.']),
                ],
            ])
            ->add('numeroCarte', TextType::class, [
                'label' => 'Numéro de carte',
                'attr'  => ['placeholder' => 'XXXX XXXX XXXX XXXX', 'maxlength' => 19],
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de carte est obligatoire.']),
                    new Regex([
                        'pattern' => '/^\d{4}\s?\d{4}\s?\d{4}\s?\d{4}$/',
                        'message' => 'Le numéro de carte doit contenir 16 chiffres.',
                    ]),
                ],
            ])
            ->add('nomPorteur', TextType::class, [
                'label' => 'Nom du porteur',
                'attr'  => ['placeholder' => 'NOM PRENOM'],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom du porteur est obligatoire.']),
                    new Length(['min' => 3, 'minMessage' => 'Le nom doit contenir au moins 3 caractères.']),
                    new Regex([
                        'pattern' => '/^[\p{L}\s]+$/u',
                        'message' => 'Le nom ne doit contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('dateExpiration', TextType::class, [
                'label' => "Date d'expiration",
                'attr'  => ['placeholder' => 'MM/AA', 'maxlength' => 5],
                'constraints' => [
                    new NotBlank(['message' => "La date d'expiration est obligatoire."]),
                    new Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/',
                        'message' => "Format invalide. Utilisez MM/AA (ex: 08/27).",
                    ]),
                    new Callback(function ($value, ExecutionContextInterface $context) {
                        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $value, $m)) return;
                        $cardYear  = 2000 + (int) $m[2];
                        $cardMonth = (int) $m[1];
                        $now = new \DateTime();
                        if ($cardYear < (int)$now->format('Y') ||
                            ($cardYear === (int)$now->format('Y') && $cardMonth < (int)$now->format('m'))) {
                            $context->addViolation('Cette carte est expirée.');
                        }
                    }),
                ],
            ])
            ->add('codeCvv', TextType::class, [
                'label' => 'Code CVV',
                'attr'  => ['placeholder' => '***', 'maxlength' => 3],
                'constraints' => [
                    new NotBlank(['message' => 'Le code CVV est obligatoire.']),
                    new Regex([
                        'pattern' => '/^\d{3}$/',
                        'message' => 'Le CVV doit contenir exactement 3 chiffres.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}