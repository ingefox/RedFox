<?php


namespace RFCore\Repositories;

use Doctrine\ORM\EntityRepository;

class R_ProjectConfigRepository extends EntityRepository
{
    public function findAllIndexed()
    {
        $qb = $this->createQueryBuilder('ProjectConfig');
        $query = $qb->indexBy('ProjectConfig', 'ProjectConfig.key')->getQuery();
        return $query->getResult();
    }
}
