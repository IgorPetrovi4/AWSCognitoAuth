<?php
declare(strict_types=1);

namespace App\Client;

interface AuthenticationClientInterface
{
    public function initiateAuth(string $username, string $password);

    public function getUser(string $token);
}