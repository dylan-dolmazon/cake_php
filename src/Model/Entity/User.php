<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;

class User extends \Cake\ORM\Entity
{

    // ... other methods

    // Automatically hash passwords when they are changed.
    protected function _setPassword(string $password)
    {
        $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
        return $hasher->hash($password);
    }

}
