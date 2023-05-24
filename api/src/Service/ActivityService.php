<?php 

namespace App\Service;

use App\Entity\Activity;
use App\Entity\ActivityQuestion;
use App\Repository\QcmRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ActivityService 
{
    public function __construct(private readonly QcmRepository $qcmRepository, private readonly Security $security)
    {
        
    }

    public function getPrefiledActivity(): Activity
    {   
        $activity = new Activity();
        $qcms = $this->qcmRepository->findAll();
        foreach ($qcms as $qcm)
        {
            $activityQuestion = new ActivityQuestion();
            $activityQuestion->setActivity($activity);
            $activityQuestion->setQuestion($qcm);
            $activityQuestion->setOwner($this->security->getUser());
            $activity->addActivityQuestion($activityQuestion);
        }
        return $activity;
    }

    

}
