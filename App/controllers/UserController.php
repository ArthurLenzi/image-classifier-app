<?php

namespace Controller;

class UserController extends \Engine\Controller
{

    public function createUser($request, $response, $args)
    {
        try {
            $body = $request->getParsedBody();
            $userService = $this->loadService('User');
            return $response->withJSON(array('response' => $userService->createUser($body)));
        } catch (CustomException | Exception $e) {
            return $response->withJson($e->getCompleteExceptionMessage(), $e->getCode());
        }
    }
}
