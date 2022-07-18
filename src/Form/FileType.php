<?php

namespace App\Form;

use App\Entity\File;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('dateImport')
            ->add('draft')
            ->add('myFile', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
                "mapped" => false,
                "label" => "Veuillez sélectionner le fichier que vous souhaitez importer: "
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => File::class,
        ]);
    }
}
