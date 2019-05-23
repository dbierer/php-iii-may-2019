<?php
declare(strict_types=1);
namespace FlyingElephantService\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Zend\Diactoros\Response\JsonResponse;
use FlyingElephantService\V1\Rest\PropulsionSystems\PropulsionSystemsResource;
 
class AuthCheckMiddleware implements MiddlewareInterface {
    const ERROR_METHOD = 'ERROR: method not supported';
    const ERROR_AUTH   = 'Not Authorized';
	protected $resource;
    protected $auth;
	public function __construct(PropulsionSystemsResource $resource, $auth) 
	{
		$this->resource = $resource;
        $this->auth     = $auth;
	}
	public function process(ServerRequestInterface $request, 
        RequestHandlerInterface $handler) : ResponseInterface 
    {
 
        $method = $request->getMethod();
        $auth   = $this->auth['authorization'][PropulsionSystemsResource::class];
        $id     = $request->getAttribute('id', 0);
        if ($id && $auth['entity'][$method]) {
            $response = $handler->handle($request);
        } elseif ($auth['collection'][$method]) {
            $response = $handler->handle($request);
        } else {
            $response = new JsonResponse(
                ['error' => self::ERROR_METHOD], StatusCode::STATUS_UNAUTHORIZED);
        }
        return $response;
    }
}
