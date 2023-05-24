<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\ActivityQuestion;
use App\Entity\QcmAnswer;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('activity', EntityType::class, [
                // looks for choices from this entity
                'class' => Activity::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'activity',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('question', EntityType::class, [
                // looks for choices from this entity
                'class' => Question::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'question',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('answer', EntityType::class, [
                // looks for choices from this entity
                'class' => QcmAnswer::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'answer',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('owner', EntityType::class, [
                // looks for choices from this entity
                'class' => User::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'username',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActivityQuestion::class,
        ]);
    }
}
