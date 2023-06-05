<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{
    #[Route('app/activity/{id}/contact', name: 'app_message')]
    #[IsGranted('ROLE_USER')]
   
    public function index(Activity $activity): Response
    {
        return $this->render('message/index.html.twig', [
            'activity' => $activity,
        ]);
    }

    #[Route('app/received', name: 'app_received')]
    #[IsGranted('ROLE_USER')]
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
    }

    #[Route('app/read/{id}', name: 'app_read')]
    #[IsGranted('ROLE_USER')]
    public function read(Message $message, EntityManagerInterface $entityManager): Response
    {
        $message->setIsRead(true);

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('message/received.html.twig', compact("message"));
    }

    #[Route('app/show', name: 'app_show')]
    #[IsGranted('ROLE_USER')]
    public function show(Message $message, EntityManagerInterface $entityManager): Response
    {
        $message->getRecipient();
        $message->getSent();
        $message->getCreatedAt();

        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('message/show.html.twig', compact("message"));
    }
}

