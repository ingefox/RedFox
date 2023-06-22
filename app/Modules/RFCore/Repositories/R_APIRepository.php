<?php


namespace RFCore\Repositories;

use Doctrine\ORM\EntityRepository;

class R_APIRepository extends EntityRepository
{
    public function findAllIndexed()
    {
        $qb = $this->createQueryBuilder('API');
        $query = $qb->indexBy('API', 'API.key')->getQuery();
        return $query->getResult();
    }
}