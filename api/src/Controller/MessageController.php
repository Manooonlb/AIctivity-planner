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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
    
        // $conversation = $conversationRepository->findForGivenUserOrCreate($activity, $user);
        $conversation = $conversationRepository->findOneBy(['activity' => $activity, 'activityParticipant' => $user]);
        if (!$conversation && !$user) {
            $conversation = new Conversation();
            $conversation->setActivity($activity)
                ->setActivityParticipant($user)
                ->setActivityOwner($activity->getOwner());
            $conversationRepository->save($conversation, true);
        }
        foreach ($conversation->getMessages() as $message) {
            $message->setIsRead(true);
        }
        $conversationRepository->save($conversation, true);
    
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
            return $this->redirectToRoute('app_home');
         }

        return $this->redirectToRoute('app_show_conversation', ['id' => $conversations[0]->getId()]);
    }


    #[Route('app/conversations/{id}/delete', name: 'app_delete_conversation')]
    #[IsGranted('ROLE_USER')]
    public function deleteConversation(
        Conversation $conversation,
        MessageRepository $messageRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) : RedirectResponse {
         /** @var User */
         $user = $this->getUser();

         $conversations = [
            ...$user->getConversationsActivitiesOwners()->getValues(),
            ...$user->getConversationsActivitiesParticipants()->getValues()
         ];

        if(
            $conversation->getActivityOwner() !== $user
            && $conversation->getActivityParticipant() !== $user
        ) {
            return $this->redirectToRoute($request->headers->get('referer'));
        };

            $entityManagerInterface->remove($conversation);
            $entityManagerInterface->flush();

        return $this->redirectToRoute('app_show_all_conversations');

    }

}

