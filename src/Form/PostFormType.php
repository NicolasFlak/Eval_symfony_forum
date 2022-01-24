<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Thread;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'placeholder' => 'Contenu',
                ]
            ])
//            ->add('user', EntityType::class, [
//                'label' => 'Nom de l\'utilisateur',
//                'class' => User::class,
//                'choice_label' => 'name'
//            ])
//            ->add('thread', EntityType::class, [
//                'label' => 'Nom du thread',
//                'class' => Thread::class,
//                'choice_label' => 'subject'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
