<?php

namespace App\Controller;

use App\Config\TokenTypeEnum;
use App\Entity\User;
use App\Service\Security\Token\TokenValidityChecker;
use App\Service\Security\UserUpdatePass;
use App\Service\Security\UserVerify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Security\UserCreate;

#[Route('/api/users')]
class UserController extends ApiController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        private readonly UserCreate $userCreate,
    ) {
        parent::__construct($entityManager, $serializer, $validator);
    }

    #[Route(path: '', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $newUser = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        $userType = json_decode($request->getContent())->type;

        // Register user
        $this->userCreate->execute($newUser, $userType);

        return  $this->json(['message' => "L'utilisateur a bien été enregistré."], Response::HTTP_OK);
    }

    #[Route(path: '/verify',methods: ['POST'])]
    public function verifiedUsed(Request $request, UserVerify $userVerify): JsonResponse
    {
        $requestContent = json_decode($request->getContent());

        // Verify token and update user
        if (!$userVerify->verifyByToken($requestContent->token)) {
            return $this->json(['message' => "Le token n'éxiste pas."], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['message' => "L'utilisateur a été vérifier."], Response::HTTP_OK);
    }

    #[Route(path: '/reset-pass',methods: ['POST'])]
    public function changePass(
        Request $request,
        TokenValidityChecker $tokenValidityChecker,
        UserUpdatePass $userUpdatePass,
    ): JsonResponse {
        $requestContent = json_decode($request->getContent());

        // Check if token is valid
        if (!$tokenValidityChecker->check(TokenTypeEnum::TOKEN_RESET_PASS, $requestContent->token)) {
            return $this->json(['message' => "Le token n'éxiste pas ou n'est plus valide."], Response::HTTP_NOT_FOUND);
        }

        // Verify and update password
        $user = $tokenValidityChecker->getUser();
        $userUpdatePass->update($user, $requestContent->password);

        return $this->json(['message' => "Le mot de passe a bien été modifié."], Response::HTTP_OK);
    }

	#[Route(path: '/retrieve-user', name: 'retrieve-user', methods: ['GET'])]
	public function retrieveUser(): JsonResponse
	{
		if(!$this->getUser()) {
			return new JsonResponse([
				'message' => "l'utilisateur n'existe pas"
			], 404);
		}

		$jsonUser = $this->serializer->serialize($this->getUser(), 'json', ['groups' => 'index']);

		return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
	}

	#[Route('/{id}', name:"updateUser", methods:['PUT'])]

	public function updateUser(Request $request, User $currentUser): JsonResponse
	{
		/** @var User $updatedUser */
		$updatedUser = $this->serializer->deserialize($request->getContent(),
			User::class,
			'json',
			[AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);

		$updatedUser->setUpdatedAt(new \DateTime('now'));

		$this->entityManager->persist($updatedUser);
		$this->entityManager->flush();

		return new JsonResponse([
			"statusCode" => 204,
			"message" =>  sprintf("L'utlisateur %s a bien été modifié.", $updatedUser->getId())
		], JsonResponse::HTTP_OK);
	}
}
