<?php

namespace models;

use lib\CustomException;

class ImageModel extends \Engine\Model
{
    public function __construct()
    {
        parent::__construct();

        $this->initDatabase('db');
    }

    public function getImage($hash)
    {
        $sqlQuery = "SELECT * from image_app.image I WHERE I.hash = :image_hash";

        $bindings = array(
            ":image_hash" => $hash
        );

        return $this->container['db']->select($sqlQuery, $bindings);
    }

    public function getRandomImage($role)
    {
        $sqlQuery = "SELECT I.hash FROM image_app.image I
        WHERE I.role = :role
        ORDER BY RANDOM()
        LIMIT 1";

        $bindings = array(
            ":role" => $role
        );

        return $this->container['db']->select($sqlQuery, $bindings);
    }

    public function saveImage($hash, $imageRole)
    {
        $insertValues = array (
            'hash' => $hash,
            'role' => $imageRole
        );

        return $this->container['db']->insert('image_app.image', $insertValues);
    }
}
