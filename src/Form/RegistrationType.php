<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class RegistrationType
 * @package App\Form
 */
class RegistrationType extends AbstractType
{

    /**
     * RegistrationType constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('profile_picture', FileType::class,[
                'attr' => ['class' => 'file_css'],
                'mapped' => false,
                'empty_data' => ''
            ])
            ->add('first_name', null, [
                'empty_data' => ''
            ])
            ->add('last_name', null, [
                'empty_data' => ''
            ])
            ->add('email', null, [
                'empty_data' => ''
            ])
        ;

        if (!$this->security->getUser()) {
            $builder->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'empty_data' => '',
                    'constraints' => [
                        new NotBlank([
                            'message' => "Merci d'entrer un mot de passe!",
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caracteres!',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])
            ;
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
