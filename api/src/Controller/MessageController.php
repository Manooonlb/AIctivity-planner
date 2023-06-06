<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Activity;
use App\Entity\Conversation;
use App\Repository\ActivityRepository;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    #[Route('app/activity/{id}/contact', name: 'app_message')]
    #[IsGranted('ROLE_USER')]
    public function index(Activity $activity, ConversationRepository $conversationRepository): Response
    {
        /** @var User */
        $user = $this->getUser();
        if($activity->getOwner()->getId() === $user->getId())
        {
            return $this->redirectToRoute('app_activity_index');
        }
        $conversation = $conversationRepository->findOneBy(['activity' => $activity,'activityParticipant' => $user]);
        if (!$conversation)
        {
            $conversation = new Conversation();
            $conversation->setActivity($activity)
                ->setActivityParticipant($user)
                ->setActivityOwner($activity->getOwner());
                ;
            $conversationRepository->save($conversation, true);    
            dump($conversation);
        }

        return $this->redirectToRoute("app_show_conversation",['id'=>$conversation->getId()]);
    }

    #[Route('app/conversations/{id}', name: 'app_show_conversation')]
    #[IsGranted('ROLE_USER')]
    public function showConversation(Conversation $conversation) : Response
    {
         /** @var User */
         $user = $this->getUser();
        if(
            $conversation->getActivityOwner() !== $user
            && $conversation->getActivityParticipant() !== $user
        ) {
            dd('degage');
        }
        // dd($user->getConversationsActivitiesOwners()->count());
        return $this->render('message/index.html.twig', [
            'conversation' => $conversation,
            'allConversations' =>[
                ...$user->getConversationsActivitiesOwners()->getValues(),
                ...$user->getConversationsActivitiesParticipants()->getValues()
            ],
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

