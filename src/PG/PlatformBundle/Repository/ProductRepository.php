<?php

namespace PG\PlatformBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
	public function findAllWithImage() {
		$qb = $this->createQueryBuilder('p');

    	return $qb->getQuery()->getResult();
	}

	public function findWithImage($id) {
		$qb = $this->createQueryBuilder('p')
				   ->where('p.id = :id')
				   ->setParameter('id', $id);

    	return $qb->getQuery()->getResult();
	}
}
