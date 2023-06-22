<?php

namespace RFCore\Repositories;

use Doctrine\ORM\EntityRepository;

class R_BOUserRepository extends EntityRepository
{
    public function findAllIndexed()
    {
        $qb = $this->createQueryBuilder('users');
        $query = $qb->indexBy('users', 'users.email')->getQuery();
        return $query->getResult();
    }
}