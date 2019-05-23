<?php
declare(strict_types=1);
namespace FlyingElephantService\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Validator\Uuid;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Zend\Diactoros\Response\JsonResponse;

class UuidCheckMiddleware implements MiddlewareInterface
{
    const ERROR_UUID = 'ERROR: improper ID';
    public function process(ServerRequestInterface $request, 
        RequestHandlerInterface $handler) : ResponseInterface {
 
        $id       = $request->getAttribute('id', 0);        
        $uuid     = new Uuid();
        $response = $handler->handle($request);
        if ($id && !$uuid->isValid($id))
            $response = new JsonResponse(
                ['error' => self::ERROR_UUID], StatusCode::STATUS_NOT_ACCEPTABLE);
        return $response;
    }
}
