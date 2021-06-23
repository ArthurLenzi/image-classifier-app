<?php

namespace Controller;

class ImageController extends \Engine\Controller
{

    public function getImage($request, $response, $args)
    {
        try {
            $imageService = $this->loadService('Image');
            return $response->withJSON($imageService->getImage());
        } catch (CustomException | Exception $e) {
            return $response->withJson($e->getCompleteExceptionMessage(), $e->getCode());
        }
    }

    public function processImage($request, $response, $args)
    {
        try {
            $imageService = $this->loadService('Image');
            return $response->withJSON($imageService->getImage());
        } catch (CustomException | Exception $e) {
            return $response->withJson($e->getCompleteExceptionMessage(), $e->getCode());
        }
    }
}
