<?php

namespace App\classes;

class PasswordManager
{
    public function __construct()
    {

    }

    public function generateRandomPass(): string{

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+!-_?';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }

    public function hashPassword(string $pass): string {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    public function verifyPass(string $pass, string $hash): bool {
        return password_verify($pass, $hash);
    }
}