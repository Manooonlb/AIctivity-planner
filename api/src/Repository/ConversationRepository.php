<?php

namespace App\Repository;

use App\Entity\Activity;
use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function save(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conversation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findForGivenUserOrCreate(Activity $activity, User $user): Conversation
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.activity = :activity')
            ->setParameter('activity', $activity)
            ->andWhere('c.activityParticipant = :user')
            ->andWhere('c.activityOwner = :owner')
            ->setParameter('user', $user)
            ->setParameter('owner', $activity->getOwner());

        $result = $qb->getQuery()->getResult();

        if (count($result) === 1) {
            return $result[0];
        }

        $conversation = new Conversation();
        $conversation->setActivity($activity)
            ->setActivityParticipant($user)
            ->setActivityOwner($activity->getOwner());
        $this->save($conversation, true);

        return $conversation;
    }
}
