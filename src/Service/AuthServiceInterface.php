<?php
declare(strict_types=1);

namespace App\Service;

interface AuthServiceInterface
{
    public function getUser(string $token) : string|array;
    public function token(string $username, string $password): ?string;
    public function cognitoUserIdentifier(string $token): ?string;
}
