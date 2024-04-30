<?php
declare(strict_types=1);

namespace App\Client;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Exception\AwsException;
use Aws\Result;
use Psr\Log\LoggerInterface;

class AWSCognitoClient implements AuthenticationClientInterface
{
    public function __construct(
        private readonly CognitoIdentityProviderClient $cognitoClient,
        private readonly string                        $userPoolId,
        private readonly string                        $clientId,
        private LoggerInterface                        $logger
    )
    {
    }

    public function initiateAuth(string $username, string $password): Result
    {
        try {
            return $this->cognitoClient->adminInitiateAuth([
                'UserPoolId' => $this->userPoolId,
                'ClientId' => $this->clientId,
                'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                'AuthParameters' => [
                    'USERNAME' => $username,
                    'PASSWORD' => $password,
                ],
            ]);
        } catch (AwsException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }

    public function getUser(string $token): Result
    {
        try {
            return $this->cognitoClient->getUser([
                'AccessToken' => $token
            ]);
        } catch (AwsException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }
    }
}