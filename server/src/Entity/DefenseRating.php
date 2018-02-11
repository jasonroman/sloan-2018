<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="defense_rating")
 * @UniqueEntity({"team", "game_date"})
 */
class DefenseRating extends AbstractRating
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"public"})
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="game_date", type="datetime", nullable=false)
     * @Groups({"public"})
     */
    protected $gameDate;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="decimal", precision=4, scale=1, nullable=false)
     * @Groups({"public"})
     */
    protected $rating;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="offenseRatings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     * @Groups({"team_assoc"})
     */
    protected $team;
}