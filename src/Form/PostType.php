<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Title : ',
                'attr' => [
                    'class' => 'form-control mb-2',
                    'placeholder' => 'Enter Post Title',
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('content',TextareaType::class,[
                'label' => 'Content : ',
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Enter Post Content',
                ],
                'label_attr' => [   
                    'class' => 'form-label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
