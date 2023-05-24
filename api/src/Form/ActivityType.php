<?php

namespace App\Form;
use App\Entity\Qcm;
use App\Entity\User;
use App\Entity\Activity;
use App\Entity\QcmAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            // ->add('description')
            ->add('duration')
            ->add('location')
            ->add('date', HiddenType::class)
            ->add('starting_time')
            ->add('outdoor')
            ->add('image', HiddenType::class)
            ->add('open')
            ->add('numberOfParticipants')
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true
            ])
            // ->add('participants', HiddenType::class)
            ->add('questions', EntityType::class,[
                'choices' => $options['qcms'],
                'class' => QcmAnswer::class,
                'choice_label' => 'question',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
            'qcms' => [],
        ]);
    }
}
