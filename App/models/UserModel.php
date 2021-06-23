<?php

namespace models;

use lib\CustomException;

class UserModel extends \Engine\Model
{
    public function __construct()
    {
        parent::__construct();

        $this->initDatabase('db');
    }

    public function getUser($email)
    {
        $sqlQuery = "SELECT * from acl.user U WHERE U.email = :email AND valid = true";

        $bindings = array(
            ":email" => $email
        );

        return $this->container['db']->select($sqlQuery, $bindings);
    }

    public function createUser($email, $passwordHash, $name)
    {
        $insertValues = array (
            'name' => $name,
            'password' => $passwordHash,
            'email' => $email
        );

        return $this->container['db']->insert('acl.user', $insertValues);
    }

    public function getRoles($email)
    {
        $sqlQuery = "SELECT U.role from acl.user_role U WHERE U.email = :email";

        $bindings = array(
            ":email" => $email
        );

        return $this->container['db']->select($sqlQuery, $bindings);
    }
}
