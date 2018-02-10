<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    /**
     * Find a team by its sport and either its city or name.
     *
     * @param string $sport
     * @param string $name
     * @return Team
     */
    public function findOneBySportAbbreviationAndCityOrName(string $sport, string $name): Team
    {
        return $this->createQueryBuilder('t')
            ->join('t.sport', 's')
            ->andWhere('s.abbreviation = :sport')
            ->andWhere('t.city = :name OR t.name = :name')
            ->setParameter('sport', $sport)
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleResult()
        ;
    }
}