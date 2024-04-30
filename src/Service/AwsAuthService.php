<?php
declare(strict_types=1);


namespace App\Service;

use App\Client\AuthenticationClientInterface;
use Aws\Exception\AwsException;


class AwsAuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly AuthenticationClientInterface $cognitoClient
    ){ }

    public function getUser(string $token): string|array
    {
        try {
            return $this->cognitoClient->getUser($token)->toArray();
        } catch (AwsException $e) {
            return $e->getAwsErrorMessage();
        }

    }

    public function cognitoUserIdentifier($token): ?string
    {
        $user = $this->getUser($token);
        if (is_string($user)) {
            throw new \Exception('User not found');
        }
        foreach ($user['UserAttributes'] as $attribute) {
            if ($attribute['Name'] == 'email') {
                return $attribute['Value'];
            }
        }
        throw new \Exception('Email not found in the user attributes');
    }

    public function token(string $username, string $password): ?string
    {
        try {
            $response = $this->cognitoClient->initiateAuth($username, $password);
            if (!isset($response['AuthenticationResult']['AccessToken'])) {
                throw new \Exception('AccessToken is not set in the response');
            }
            return $response['AuthenticationResult']['AccessToken'];
        } catch (AwsException $e) {
            return $e->getAwsErrorMessage();
        }
    }

}
