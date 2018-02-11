<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @ORM\Table(name="team")
 * @UniqueEntity({"sport", "city"})
 */
class Team
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"public"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", unique=true, length=50)
     * @Groups({"public"})
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="team_name", type="string", unique=true, length=50)
     * @Groups({"public"})
     */
    private $name;

    /**
     * @var Sport
     *
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="teams")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     * })
     * @Groups({"sport_assoc"})
     */
    private $sport;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OffenseRating", mappedBy="team")
     * @Groups({"offense_ratings_assoc"})
     */
    private $offenseRatings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DefenseRating", mappedBy="team")
     * @Groups({"defense_ratings_assoc"})
     */
    private $defenseRatings;

    /**
     * Initialize empty collections for OneToMany associated entities.
     */
    public function __construct()
    {
        $this->offenseRatings = new ArrayCollection();
        $this->defenseRatings = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @Groups({"public"})
     * @return string
     */
    public function getFullName(): string
    {
        return $this->city.' '.$this->name;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Sport
     */
    public function getSport(): Sport
    {
        return $this->sport;
    }

    /**
     * @return ArrayCollection
     */
    public function getOffenseRatings()
    {
        return $this->offenseRatings;
    }

    /**
     * @return ArrayCollection
     */
    public function getDefenseRatings()
    {
        return $this->defenseRatings;
    }

    /**
     * @Groups({"offense_ratings_assoc"})
     * @return float
     */
    public function getAverageOffenseRating(): float
    {
        return $this->getAverageRating($this->offenseRatings);
    }

    /**
     * @Groups({"defense_ratings_assoc"})
     * @return float
     */
    public function getAverageDefenseRating(): float
    {
        return $this->getAverageRating($this->defenseRatings);
    }

    /**
     * @param ArrayCollection $ratings
     * @return float
     */
    private function getAverageRating($ratings): float
    {
        $sum = 0.0;

        if (!count($ratings)) {
            return (float) 0;
        }

        /** @var AbstractRating $rating */
        foreach ($ratings as $rating) {
            $sum += $rating->getRating();
        }

        return number_format((float) $sum / (float) count($ratings), 1);
    }
}