<?php

namespace App\Controller;

use App\Entity\Sport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/sports")
 */
class SportsApiController extends Controller
{
    /**
     * @Route("/", name="api_sports")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function sports(SerializerInterface $serializer)
    {
        /** @var Sports[] $sports */
        $sports     = $this->getDoctrine()->getRepository(Sport::class)->findAll();
        $jsonSports = $serializer->serialize($sports, 'json', ['groups' => ['public', 'teams_assoc']]);

        return JsonResponse::fromJsonString($jsonSports);
    }

    /**
     * @Route("/no-associations", name="api_sports_no_associations")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function sportsNoAssociations(SerializerInterface $serializer)
    {
        /** @var Sport[] $teams */
        $sports      = $this->getDoctrine()->getRepository(Sport::class)->findAll();
        $jsonSports = $serializer->serialize($sports, 'json', ['groups' => ['public']]);

        return JsonResponse::fromJsonString($jsonSports);
    }

    /**
     * @Route("/{sport}", name="api_sports_sport")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $sport
     * @return JsonResponse
     */
    public function sport(SerializerInterface $serializer, string $sport)
    {
        /** @var Sport $sport */
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneByAbbreviation($sport);

        if (!$sport) {
            throw $this->createNotFoundException('Sport does not exist');
        }

        $jsonSport = $serializer->serialize($sport, 'json', ['groups' => ['public', 'teams_assoc']]);

        return JsonResponse::fromJsonString($jsonSport);
    }

    /**
     * @Route("/{sport}/teams", name="api_sports_sport_teams")
     * @Method({"GET"})
     *
     * @param SerializerInterface $serializer
     * @param string $sport
     * @return JsonResponse
     */
    public function sportTeams(SerializerInterface $serializer, string $sport)
    {
        /** @var Sport $sport */
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneByAbbreviation($sport);

        if (!$sport) {
            throw $this->createNotFoundException('Sport does not exist');
        }

        $jsonSport = $serializer->serialize($sport->getTeams(), 'json', ['groups' => ['public']]);

        return JsonResponse::fromJsonString($jsonSport);
    }
}
