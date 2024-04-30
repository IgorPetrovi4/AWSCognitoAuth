<?php
declare(strict_types=1);


namespace App\Controller;

use App\Service\AuthServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;


class AuthController extends AbstractController
{

    public function __construct(
        private readonly AuthServiceInterface $authService,
    ){ }


    #[Route('/api/login', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the user token upon successful authentication',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'),
            ]
        )
    )]
    #[OA\RequestBody(
        description: "User's credentials",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'test@vireye.com'),
                new OA\Property(property: 'password', type: 'string', example: 'Vireye1!'),
            ]
        )
    )]
    #[OA\Tag(name: 'Authentication')]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $token = $this->authService->token($email, $password);
        return $this->json(['token' => $token]);
    }

}