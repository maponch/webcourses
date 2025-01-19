<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate', IntegerType::class, [
                'label' => 'Note du cours (1 à 5)'
            ])
            ->add('content', TextAreaType::class, [
                'label' => 'Votre commentaire',
            ])
            ->add('date', DateType::class, [
                'label' => 'Date du commentaire',
                'widget' => 'single_text', // Affiche le champ sous forme d'un seul champ de texte
                'required' => false, // Facultatif si vous ne souhaitez pas que l'utilisateur doive le remplir manuellement
                'data' => new \DateTimeImmutable(), // Valeur par défaut (actuelle)
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
