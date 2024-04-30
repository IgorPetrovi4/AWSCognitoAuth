<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[Route('api/user')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository              $userRepository,
        private readonly ValidatorInterface          $validator,
        private readonly EntityManagerInterface      $entityManager
    ){ }


    #[Route('/balance', name: 'get_user_balance', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get user balance'
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function getUserBalance(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json(['message' => 'Access denied .'], Response::HTTP_NOT_FOUND);
        }

        $currencyBalances = $this->userRepository->getUserBalances($user);

        return $this->json(['currency' => $currencyBalances], Response::HTTP_OK);
    }


    #[Route('/update/balance', name: 'update_user_balance', methods: ['POST'])]
    #[OA\RequestBody(
        description: "Update user balance",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'USDT', type: 'string', example: '1500.3458989'),
                new OA\Property(property: 'Gold', type: 'string', example: '25.212')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Balance updated successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Balance updated successfully')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Invalid data provided'
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]

    public function updateUserBalance(Request $request): JsonResponse
    {

        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json(['message' => 'Access denied .'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        foreach ($data as $currency => $amount) {
            $balance = new Balance();
            $balance->setUser($user);
            $balance->setCurrency($currency);
            $balance->setAmount($amount);
            $errors = $this->validator->validate($balance);
            if (count($errors) > 0) {
                $errorsArray = [];
                foreach ($errors as $error) {
                    $errorsArray[$error->getPropertyPath()][] = $error->getMessage();
                }
                return $this->json(['errors' => $errorsArray], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($balance);
        }
        $this->entityManager->flush();

        return $this->json(['message' => 'Balance updated successfully'], Response::HTTP_CREATED);
    }

}
