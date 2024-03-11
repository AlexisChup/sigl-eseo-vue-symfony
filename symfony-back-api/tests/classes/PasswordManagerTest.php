<?php

namespace App\Tests\classes;

use App\classes\PasswordManager;
use PHPUnit\Framework\TestCase;

class PasswordManagerTest extends TestCase
{
    public function testHashPassword(){

        $passManager = new PasswordManager();
        $pass = $passManager->generateRandomPass();
        $hash = $passManager->hashPassword($pass);
        $this->assertTrue($passManager->verifyPass($pass, $hash));
    }
}
