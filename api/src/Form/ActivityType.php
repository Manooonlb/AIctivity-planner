<?php

namespace App\Form;
use App\Entity\Activity;
use App\Entity\User;
use App\Form\ActivityQuestionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
        $years = range(date('Y'), date('Y')+2);
        $builder
            ->add('name')
            ->add('description')
            ->add('duration')
            ->add('location')
            ->add('date',DateType::Class, array(
                'widget' => 'choice',
                'years' => $years,
            'data' => new \DateTime(),
                
              ))
            ->add('starting_time')
            ->add('outdoor')
            ->add('open')
            ->add('numberOfParticipants')
            // ->add('owner', EntityType::class,[
            //     'class' => User::class,
            //     'choice_label' => 'username',
            // ])
            ->add('activityQuestions', CollectionType::class, [
                'entry_type' => ActivityQuestionType::class,
                'entry_options' => ['label' => false],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
