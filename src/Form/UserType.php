<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>false,
                'download_link'=>false,
                'required'=>false,
                'constraints' => [
                    new File([
                        'maxSize' => '100k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png',

                        ],
                        'mimeTypesMessage'=>'format de l\'image est invalide',
                        'maxSizeMessage'=>'la taille de l\'image est trop elevé',

                    ]),

                ],


            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
