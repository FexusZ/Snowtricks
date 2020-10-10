<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgotPasswordType extends AbstractType
{

    public function __construct(Security $security)
    {
        $this->security = $security;
    } 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, [
                'empty_data' => '',
                'constraints' => [
                    new Email([
                        'message' => "Merci d'entrer une Email valide!",
                    ]),
                    new NotBlank([
                        'message' => "Merci d'entrer votre Email!",
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        
    }
}
