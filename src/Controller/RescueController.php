<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Association;
use App\Entity\Rescue;
use App\Entity\WithdrawalPoint;
use App\Exception\EntityNotFoundException;
use App\Repository\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


#[Route('/api/rescues')]
class RescueController extends ApiController
{

	protected static int $defaultPerPage = 6;
    protected static array $defaultOrderBy = [
        'r.id' => 'ASC',
    ];

	#[Route(path: '', methods: 'GET')]
    public function index(Request $request): JsonResponse
    {
        $currentPage = (int)$request->get('page', static::$defaultPage);
        $perPage = (int)$request->get('items_per_page', static::$defaultPerPage);

        $total = $this->repository->countWhereActive();

        $from = ($currentPage - 1) * $perPage;
        $to = min([$total - $from, $from + $perPage]);

        $records = $this->repository->findWhereActive(static::$defaultOrderBy, $perPage, $from);
        return $this->json(
            [
                'data' => $records,
                'meta' => [
                    'current_page' => $currentPage,
                    'items_per_page' => $perPage,
                    'last_page' => ceil($total / $perPage),
                    'from' => $from + 1,
                    'to' => $to,
                    'total' => $total,
                ],
            ],
            Response::HTTP_OK,
            [],
            ['groups' => 'index']
        );
    }

	protected function relatedRelations($rescue)
	{
		$association = $this->entityManager->getRepository(Association::class)->find($rescue->getAssociationId());

		if (is_null($association)) {
			throw new EntityNotFoundException(static::getRelatedEntity(), (int)$rescue->getAssociationId());
		}
		$rescue->setAssociation($association);

		return $rescue;
	}
}
