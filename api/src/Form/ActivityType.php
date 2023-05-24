<?php

namespace App\Form;
use App\Entity\Qcm;
use App\Entity\User;
use App\Entity\Activity;
use App\Entity\ActivityQuestion;
use App\Entity\QcmAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('duration')
            ->add('location')
            ->add('date')
            ->add('starting_time')
            ->add('outdoor', CheckboxType::class)
            ->add('open')
            ->add('numberOfParticipants')
            ->add('owner', HiddenType::class)
            ->add('participants', HiddenType::class)
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
            'qcms' => [],
        ]);
    }
}
