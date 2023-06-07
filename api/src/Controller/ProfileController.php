<?php

namespace App\Controller;

use App\Form\UserEditType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ProfilePictureType;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/app/myprofile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_my', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged in user
        $user = $this->getUser();

        // Create the form with the user's data
        $form = $this->createForm(UserEditType::class, $user);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update the user's profile data
            $entityManager->flush();

            // Redirect to the profile page or display a success message
            return $this->redirectToRoute('app_profile_my');
        }

        return $this->render('profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editProfileUpload(Request $request, UserRepository $userRepository): Response
    {
        // Get the currently logged in user
        $user = $this->getUser();

        // Create the form with the user's data
        $form = $this->createForm(UserEditType::class, $user);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Handle profile picture upload
            $profilePicture = $form->get('imageFile')->getData();
            if ($profilePicture) {
                // Generate a unique filename
                $newFilename = uniqid() . '.' . $profilePicture->guessExtension();

                try {
                    // Move the uploaded file to the desired directory
                    $profilePicture->move(
                        $this->getParameter('profile_picture_directory'),
                        $newFilename
                    );

                    // Update the user's profile picture filename in the database
                    $user->setAvatarPath($newFilename);
                    // dd($user);
                    $userRepository->save($user, true);
                } catch (FileException $e) {
                    dd($e->getMessage());
                    // Handle error when file cannot be moved
                    // You can redirect back to the edit page with an error message
                    return $this->redirectToRoute('app_profile_edit');
                }
            }
            $userRepository->save($user, true);

            // Redirect to the profile page or display a success message
            return $this->redirectToRoute('app_profile_my');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/upload-picture', name: 'app_profile_upload_picture', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function uploadPicture(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged in user
        $user = $this->getUser();

        // Create the form for profile picture upload
        $form = $this->createForm(ProfilePictureType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the uploaded picture file
            $profilePicture = $form->get('imageFile')->getData();

            // Generate a unique filename
            $newFilename = uniqid() . '.' . $profilePicture->guessExtension();

            try {
                // Move the uploaded file to the desired directory
                $profilePicture->move(
                    $this->getParameter('profile_picture_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle error when file cannot be moved
                // You can redirect back to the profile page with an error message
                return $this->redirectToRoute('app_profile_edit');
            }

            // Update the user's profile picture filename in the database
            $user->setProfilePicture($newFilename);
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to the profile page or display a success message
            return $this->redirectToRoute('app_profile_my');
        }

        return $this->render('profile/form.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/delete', name: 'app_profile_delete', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function deleteProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the currently logged in user
        $user = $this->getUser();

        // Check if the form was submitted
        if ($request->isMethod('POST')) {
            // Delete the user's profile data
            $entityManager->remove($user);
            $entityManager->flush();

            // Redirect to a success page or display a success message
            return $this->redirectToRoute('app_profile_deleted');
        }

        return $this->render('profile/delete.html.twig', [
            'user' => $user,
        ]);
    }

}