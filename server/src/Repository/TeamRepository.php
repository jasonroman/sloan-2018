<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    /**
     * Find all teams by their league.
     *
     * @param string $league
     * @return Team
     */
    public function findAllByLeague(string $league)
    {
        return $this->createQueryBuilder('t')
            ->join('t.league', 'l')
            ->andWhere('l.abbreviation = :league')
            ->setParameter('league', $league)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Find a team by its league and either its city or name.
     *
     * @param string $league
     * @param string $name
     * @return Team
     */
    public function findOneByLeagueAbbreviationAndCityOrName(string $league, string $name): Team
    {
        return $this->createQueryBuilder('t')
            ->join('t.league', 'l')
            ->andWhere('l.abbreviation = :league')
            ->andWhere('t.city = :name OR t.name = :name')
            ->setParameter('league', $league)
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleResult()
            ;
    }
}