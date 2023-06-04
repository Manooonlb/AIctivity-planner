<?php

namespace App\Form;

use App\Entity\ProfilePicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user']; // Get the user from options

        $builder->add('imageFile', FileType::class, [
            'label' => 'Profile Picture',
            'required' => false,
        ]);
     if ($user instanceof UserInterface) {
        $builder->setData(new ProfilePicture());
    }
}
   

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => ProfilePicture::class,
        'user' => null, // Add the 'user' option with a default value of null
    ]);

    $resolver->setAllowedTypes('user', ['null', UserInterface::class]);
}

}
