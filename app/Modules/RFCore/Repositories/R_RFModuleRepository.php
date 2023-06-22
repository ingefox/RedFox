<?php

namespace RFCore\Repositories;

use Doctrine\ORM\EntityRepository;

class R_RFModuleRepository extends EntityRepository
{
	public function findAllIndexed()
	{
		$qb = $this->createQueryBuilder('RFModules');
		$query = $qb->indexBy('RFModules', 'RFModules.name')->getQuery();
		return $query->getResult();
	}
}
