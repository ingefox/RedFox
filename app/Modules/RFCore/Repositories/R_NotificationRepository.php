<?php


namespace RFCore\Repositories;

use Doctrine\ORM\EntityRepository;

class R_NotificationRepository extends EntityRepository
{
    public function findNotifsToArchive($limitDate)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('n')
            ->from('RFCore\Entities\E_Notification', 'n')
            ->where('n.date > :limitDate')
            ->orderBy('n.date', 'asc')
            ->setParameter('limitDate', $limitDate);

        $query = $qb->getQuery();
        $result =  $query->getResult();

        return $result;
    }
}
