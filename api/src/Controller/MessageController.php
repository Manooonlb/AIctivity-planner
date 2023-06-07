<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Activity;
use App\Entity\Conversation;
use App\Repository\ActivityRepository;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MessageController extends AbstractController
{

    #[Route('app/activity/{id}/contact', name: 'app_message')]
    #[IsGranted('ROLE_USER')]
    public function index(Activity $activity, ConversationRepository $conversationRepository, Security $security, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $security->getUser();
        if ($activity->getOwner() === $user) {
            return $this->redirectToRoute('app_activity_index');
        }
    
        $conversation = $conversationRepository->findOneBy(['activity' => $activity, 'activityParticipant' => $user]);
        if (!$conversation) {
            $conversation = new Conversation();
            $conversation->setActivity($activity)
                ->setActivityParticipant($user)
                ->setActivityOwner($activity->getOwner());
            $conversationRepository->save($conversation, true);
        } else {
            // Mark all messages in the conversation as read
            foreach ($conversation->getMessages() as $message) {
                $message->setIsRead(true);
            }
            $conversationRepository->save($conversation, true);
        }
    
        return $this->redirectToRoute('app_show_conversation', ['id' => $conversation->getId()]);

    }
    

    #[Route('app/conversations/{id}', name: 'app_show_conversation')]
    #[IsGranted('ROLE_USER')]
    public function showConversation(
        Conversation $conversation,
        HubInterface $hub,
        MessageRepository $messageRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) : Response {
         /** @var User */
         $user = $this->getUser();
        if(
            $conversation->getActivityOwner() !== $user
            && $conversation->getActivityParticipant() !== $user
        ) {
            return $this->redirectToRoute($request->headers->get('referer'));
        }

        $entityManagerInterface->beginTransaction();
        foreach ($conversation->getMessages()->filter(function(Message $message) use ($user) {
            return $message->getRecipient() === $user && !$message->isIsRead();
        }) as $message)
        {
            // Vérifier si le message n'a pas encore été lu
            $message->setIsRead(true);
            $messageRepository->save($message, true);
        }
       $entityManagerInterface->commit();
        return $this->render('message/index.html.twig', [
            'conversation' => $conversation,
            'allConversations' =>[
                ...$user->getConversationsActivitiesOwners()->getValues(),
                ...$user->getConversationsActivitiesParticipants()->getValues()
            ],
        ]);
    }

    #[Route('app/conversations', name: 'app_show_all_conversations')]
    #[IsGranted('ROLE_USER')]
    public function showAllConversations(Request $request) : Response
    {
         /** @var User */
         $user = $this->getUser();

         $conversations = [
            ...$user->getConversationsActivitiesOwners()->getValues(),
            ...$user->getConversationsActivitiesParticipants()->getValues()
         ];

         if (!count($conversations)) {
            return $this->redirectToRoute($request->headers->get('referer'));
         }

        return $this->redirectToRoute('app_show_conversation', ['id' => $conversations[0]->getId()]);
    }




    #[Route('app/received', name: 'app_received')]
    #[IsGranted('ROLE_USER')]
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
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

