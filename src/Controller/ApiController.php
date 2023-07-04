<?php

namespace App\Controller;

use App\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ApiController extends AbstractController
{
    protected static int $defaultPage = 1;

    protected static int $defaultPerPage = 10;

    protected static ?string $relatedEntity = null;

    protected static array $defaultOrderBy = [
        'id' => 'ASC',
    ];

    protected static array $defaultCriteria = [];

    protected EntityRepository $repository;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface    $serializer,
        protected ValidatorInterface     $validator,
    )
    {
        $this->repository = $this->entityManager->getRepository(static::getRelatedEntity());
    }

    protected static function getRelatedEntity(): string
    {
        return static::$relatedEntity ?: static::resolveEntityName(get_called_class());
    }

    private static function resolveEntityName(string $controllerName): array|string
    {
        return str_replace(
            'Controller',
            '',
            str_replace('App\\Controller\\', 'App\\Entity\\', $controllerName)
        );
    }

    #[Route(path: '', methods: 'GET')]
    public function index(Request $request): JsonResponse
    {
        $currentPage = (int)$request->get('page', static::$defaultPage);
        $perPage = (int)$request->get('items_per_page', static::$defaultPerPage);

        $total = $this->repository->count([]);
        $from = ($currentPage - 1) * $perPage;
        $to = min([$total - $from, $from + $perPage]);

        $records = $this->repository->findBy(static::$defaultCriteria, static::$defaultOrderBy, $perPage, $from);
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

    #[Route(path: '', methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
        $entity = $this->serializer->deserialize($request->getContent(), static::getRelatedEntity(), 'json');

		/** persist Relations if exist */
		$entity = $this->relatedRelations($entity);

        $errors = $this->validator->validate($entity);
        if (count($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->json($entity, Response::HTTP_OK, [], ['groups' => 'show']);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route(path: '/{id}', methods: 'GET')]
    public function show($id): JsonResponse
    {
        $entity = $this->repository->find((int)$id);

        if (is_null($entity)) {
            throw new EntityNotFoundException(static::getRelatedEntity(), (int)$id);
        }

        return $this->json($entity, Response::HTTP_OK, [], ['groups' => 'show']);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route(path: '/{id}', methods: 'PUT')]
    public function edit(int $id, Request $request): JsonResponse
    {
        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            throw new EntityNotFoundException(static::getRelatedEntity(), $id);
        }

        $updatedEntity = $this->serializer->deserialize(
            $request->getContent(),
            static::getRelatedEntity(),
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $entity]
        );

        $errors = $this->validator->validate($updatedEntity);
        if (count($errors)) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($updatedEntity);
        $this->entityManager->flush();

        return $this->json($updatedEntity, Response::HTTP_OK, [], ['groups' => 'show']);
    }

    /**
     * @throws EntityNotFoundException
     */
    #[Route(path: '/{id}', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $entity = $this->repository->find($id);

        if (is_null($entity)) {
            throw new EntityNotFoundException(static::getRelatedEntity(), $id);
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    protected function json(mixed $data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if (!is_array($data) || !array_key_exists('data', $data)) {
            $data = ['data' => $data];
        }

        return parent::json(
            $data,
            $status,
            $headers,
            $context
        );
    }

	protected function relatedRelations($entity)
	{
		return $entity;
	}
}