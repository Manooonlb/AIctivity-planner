<?php

namespace App\Controller;

use App\Form\UserEditType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $form = $this->createForm(RegistrationFormType::class, $user);

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
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
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

        return $this->render('profile/edit.html.twig', [
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