<?php

namespace Controller;

class AuthController extends \Engine\Controller
{
    public function authWithJWT($request, $response, $next)
    {
        $request = $request->withAttribute('JWT', 'false');
        $request = $request->withAttribute('JWT_data', '');

        if (!$request->hasHeader('HTTP_AUTHORIZATION')) {
            return $response->withJSON(array("Erro" => 'HTTP_AUTHORIZATION is missing'));
        }

        try {
            $authService = $this->loadService('Auth');
            $decoded = $authService->decodeJWT($request->getHeaderLine('HTTP_AUTHORIZATION'));
            // stdClass to array
            $decoded = json_decode(json_encode($decoded), true);

            $request = $request->withAttribute('JWT', 'true');
            $request = $request->withAttribute('JWT_data', $decoded['JWT_data']);
        } catch (\Throwable $th) {
            return $response->withJSON(array("Erro" => $th->getMessage()));
        }
        return $next($request, $response);
    }

    public function login($request, $response, $next)
    {
        try {
            $authService = $this->loadService('Auth');
            $body = $request->getParsedBody();
            return $response->withJSON($authService->login($body));
        } catch (CustomException | Exception $e) {
            return $response->withJson($e->getCompleteExceptionMessage(), $e->getCode());
        }
    }
}
