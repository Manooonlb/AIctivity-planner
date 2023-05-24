<?php 

namespace App\Service;

use App\Entity\Activity;

class ActivityService 
{
    public function getPrefiledActivity(): Activity
    {   
        $activity = new Activity();
        return $activity;
    }

}
// use questionrepository pour recup toutes les questions puis boucle et instancie activityquestion
