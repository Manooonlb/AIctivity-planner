<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Repository\QcmRepository;
use App\Service\ActivityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;


#[Route('/app/activity')]
#[IsGranted('ROLE_USER')]
class ActivityController extends AbstractController
{
    #[Route('/', name: 'app_activity_index', methods: ['GET'])]
    public function index(ActivityRepository $activityRepository, Request $request ): Response
    {
        $page = $request->query->get('page',1);
        $itemsPerPage = 10 ;
        $list = $activityRepository->findBy( ['open'=>true], ['date'=>'ASC'], $itemsPerPage, ($page - 1) * $itemsPerPage) ;
        return $this->render('activity/index.html.twig', [
            'activities' => $list
            
        ]);
    }

    #[Route('/my/', name: 'app_activity_my', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function myIndex(ActivityRepository $activityRepository): Response
    {
        return $this->render('activity/index.html.twig', [
            'activities' => $activityRepository->findBy(['owner'=>$this->getUser()]) 
         
        ]);
    }

    // #[Route('/new', name: 'app_activity_new', methods: ['GET', 'POST'])]
    // #[IsGranted('ROLE_USER')]
    // public function new(Request $request, ActivityService $activityService, ActivityRepository $activityRepository, QcmRepository $qcmRepository): Response
    // {
    //     $activity = $activityService->getPrefiledActivity();
    //     $form = $this->createForm(ActivityType::class, $activity);
    //     $form->handleRequest($request);
    
    //     $session = $request->getSession();
    //     if (!$session->has('current_step')) {
    //         $session->set('current_step', 1);
    //     }
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $activity->setOwner($this->getUser());
    //         $activityRepository->save($activity, true);
    
    //         // Mettez à jour la session current_step pour passer à l'étape suivante
    //         $session->set('current_step', $session->get('current_step') + 1);
    
    //         return $this->redirectToRoute('app_activity_new', [], Response::HTTP_SEE_OTHER);
    //     }
    
    //     return $this->render('activity/new.html.twig', [
    //         'activity' => $activity,
    //         'form' => $form->createView(),
    //         'current_step' => $session->get('current_step'),
    //     ]);
    // }

    #[Route('/new', name: 'app_activity_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, ActivityService $activityService, ActivityRepository $activityRepository, QcmRepository $qcmRepository): Response
    {
        $activity = $activityService->getPrefiledActivity();
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        $session = $request->getSession();
        if ($form->isSubmitted() && $form->isValid()) {
            $activity->setOwner($this->getUser());
            $activityRepository->save($activity, true);

            // Réinitialiser la session pour recommencer depuis l'étape 1
            $session->remove('current_step');

            // Rediriger vers la liste des activités
            return $this->redirectToRoute('app_activity_my');
        }

        if (!$session->has('current_step')) {
            $session->set('current_step', 1);
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
            'current_step' => $session->get('current_step'),
        ]);
    }
    
    #[Route('/{id}', name: 'app_activity_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Activity $activity): Response
    {
        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activity_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activity->setOwner($this->getUser());
            $activityRepository->save($activity, true);

            return $this->redirectToRoute('app_activity_my', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activity_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->request->get('_token'))) {
            $activityRepository->remove($activity, true);
        }

        return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
    }
}
