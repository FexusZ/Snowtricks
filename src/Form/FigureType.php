<?php

namespace App\Form;

use App\Entity\Figures;
use App\Form\ImageType;
use App\Form\VideoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('figure', null, [
                'empty_data' => ''
            ])
            ->add('description', null, [
                'empty_data' => ''
            ])
            ->add('groupe', ChoiceType::class,[
                'choices' => $this->getGroup(),
                'empty_data' => 0
            ])
            ->add('images', ImageType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Images : ',
            ])
            ->add('videos', VideoType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Vidéos : '
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figures::class,
        ]);
    }
    private function getGroup()
    {
        $group = Figures::GROUP;
        $output = [];

        foreach ($group as $key => $value){
            $output[$value] = $key;
        }

        return $output;
    }
}
