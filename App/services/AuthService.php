<?php

namespace services;

use lib\CustomException;
use Firebase\JWT\JWT;

class AuthService extends \Engine\Service
{
    public const AUTH = 'Auth';

    public function generateJWT($data)
    {
        $key = $this->config['key_JWT'];

        //Tempo de expiração
        $exp = time() + (1800);
        $payload = array(
            "JWT_data" => $data,
            //Timestamp que foi gerado
            'iat' => time(),
            'exp' => $exp
        );
        return array("token" => JWT::encode($payload, $key['key_JWT']),"exp" => $exp);
    }

    public function decodeJWT($JWT)
    {
        $key = $this->config['key_JWT'];
        JWT::$leeway = 60;
        return JWT::decode($JWT, $key['key_JWT'], array('HS256'));
    }

    public function login($body)
    {
        $rules = array (
            'email' => REQUIRED . '|between:5,50',
            'password' => REQUIRED . '|between:6,50',
        );

        $this->validator->validate($body, $rules);
        $userModel = $this->loadModel('User');

        $passwordHash = hash('sha256', $body['password']);
        $user = $userModel->getUser($body['email']);

        if (empty($user) || ($user[0]['password'] != $passwordHash)) {
            throw new CustomException("Incorrect email or password.", 422);
        }

        $userRoles = $userModel->getRoles($body['email']);
        $roles = array();
        foreach ($userRoles as $role) {
            array_push($roles, $role['role']);
        }

        $token = $this->generateJWT(array(
            "name" => $user[0]['name'],
            "email" => $user[0]['email'],
            "roles" => $roles
        ));

        return array("token" => $token['token']);
    }
}
