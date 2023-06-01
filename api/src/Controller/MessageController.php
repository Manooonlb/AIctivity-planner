<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{
    #[Route('app/message', name: 'app_message')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route('app/send', name: 'app_send')]
    #[IsGranted('ROLE_USER')]
    public function send(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            $message->setSent($this->getUser());
            $message->setIsRead(false);
            $message->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('message','Good news! Message sent');
            return $this->redirectToRoute("app_received");
        }

        return $this->render('message/send.html.twig', [
            "messageForm"=> $form->createView(),
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
}

