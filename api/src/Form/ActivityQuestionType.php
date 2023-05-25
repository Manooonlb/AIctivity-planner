<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\ActivityQuestion;
use App\Entity\Qcm;
use App\Entity\QcmAnswer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('activity', EntityType::class, [
            //     // looks for choices from this entity
            //     'class' => Activity::class,
            
            //     // uses the User.username property as the visible option string
            //     'choice_label' => 'activity',
            
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     // 'expanded' => true,
            // ])
            // ->add('question', EntityType::class, [
            //     // looks for choices from this entity
            //     'class' => Qcm::class,
            
            //     // uses the User.username property as the visible option string
            //     'choice_label' => 'question',
            
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     // 'expanded' => true,
            // ])
            ->add('answer', EntityType::class, [
                'class' => QcmAnswer::class,            
                'choice_label' => 'answer',
            ])
            // ->add('owner', EntityType::class, [
            //     // looks for choices from this entity
            //     'class' => User::class,
            
            //     // uses the User.username property as the visible option string
            //     'choice_label' => 'username',
            
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     // 'expanded' => true,
            // ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) 
        {
            /** @var ActivityQuestion */
            $data = $event->getData();
            $form = $event->getForm();
            $form->add('answer', EntityType::class, 
            [
                'class' => QcmAnswer::class,    
                'label'=> $data->getQuestion()->getQuestion(),        
                'choice_label' => 'answer',
                'choices'=> $data->getQuestion()->getAnswers(),
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActivityQuestion::class,
        ]);
    }
}
