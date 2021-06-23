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

    public function saveImage($hash, $imageRole)
    {
        $insertValues = array (
            'hash' => $hash,
            'hole' => $imageRole
        );

        return $this->container['db']->insert('image_app.image', $insertValues);
    }
}
