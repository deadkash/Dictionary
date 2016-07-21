<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Dictionary;
use Doctrine\ORM\EntityRepository;

class DictionaryRepository extends EntityRepository
{

    /**
     * @param array $usedWords
     * @return Dictionary
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRandomWord($usedWords = array())
    {
        $queryBuilder = $this->createQueryBuilder('d')
            ->addSelect('RAND() as HIDDEN rand')
            ->addOrderBy('rand')
            ->setMaxResults(1);

        if ($usedWords) {
            $queryBuilder->andWhere('d.id NOT IN (:usedWords)')
                ->setParameter('usedWords', $usedWords);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $word
     * @param $count
     * @return array
     */
    public function getOtherWords($word, $count)
    {
        return $this->createQueryBuilder('d')
            ->addSelect('RAND() as HIDDEN rand')
            ->addOrderBy('rand')
            ->andWhere('d.id != :word')
            ->setParameter('word', $word)
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }
}
