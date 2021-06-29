<?php

namespace Controller;

class ImageController extends \Engine\Controller
{

    public function getImage($request, $response, $args)
    {
        try {
            $imageService = $this->loadService('Image');
            $jwtData = $request->getAttribute('JWT_data');
            return $response->withJSON($imageService->getImage($args, $jwtData));
        } catch (CustomException | Exception $e) {
            return $response->withJson($e->getCompleteExceptionMessage(), $e->getCode());
        }
    }

    public function showImage($request, $response, $args)
    {
        try {
            $imageService = $this->loadService('Image');
            $jwtData = $request->getAttribute('JWT_data');
            $response->write($imageService->showImage($args, $jwtData));
            return $response->withHeader('Content-Type', 'image/png');
        } catch (CustomException | Exception $e) {
            return $response->withJson($e->getCompleteExceptionMessage(), $e->getCode());
        }
    }

    public function processImages($request, $response, $args)
    {
        try {
            $imageService = $this->loadService('Image');
            return $response->withJSON($imageService->processAllImages('ressaca'));
        } catch (CustomException | Exception $e) {
            return $response->withJson($e->getCompleteExceptionMessage(), $e->getCode());
        }
    }
}
