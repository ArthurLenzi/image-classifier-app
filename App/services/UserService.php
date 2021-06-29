<?php

namespace services;

use lib\CustomException;

class UserService extends \Engine\Service
{
    public const USER = 'User';

    public function createUser($body)
    {
        $rules = array (
            'email' => REQUIRED . '|between:5,50',
            'password' => REQUIRED . '|between:6,50',
            'name' => REQUIRED . '|between:5,12',
        );

        $this->validator->validate($body, $rules);

        $userModel = $this->loadModel(self::USER);

        if (!empty($userModel->getUser($body['email']))) {
            throw new CustomException("There is already a registered user with this email.", 422);
        }

        $passwordHash = hash('sha256', $body['password']);

        $userModel->createUser($body['email'], $passwordHash, $body['name']);

        return "User created successfully";
    }

    public function checkUserRole($role, $jwtData)
    {
        if (!in_array($role, $jwtData['roles'])) {
            throw new CustomException("You don't have permissions in this role.", 422);
        }
        return true;
    }
}
