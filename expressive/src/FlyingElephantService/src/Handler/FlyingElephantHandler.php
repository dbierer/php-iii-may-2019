<?php
declare(strict_types=1);
namespace FlyingElephantService\Handler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Zend\Diactoros\Response\JsonResponse;
use FlyingElephantService\V1\Rest\PropulsionSystems\PropulsionSystemsResource;
 
class FlyingElephantHandler implements RequestHandlerInterface 
{
    const ERROR_METHOD = 'ERROR: method not supported';
	protected $resource;
	public function __construct(PropulsionSystemsResource $resource) {
		$this->resource = $resource;
	}
	public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        // Decode to a standard class object
        $result = [];
        $method = $request->getMethod();
        $id     = $request->getAttribute('id', 0);
        $data   = $request->getBody()->__toString() 
                ? json_decode($request->getBody()->__toString()) 
                : null;
 
        if($id){
			switch($method){
				case 'GET' :
					$result['data'] = $this->resource->fetch($id);
					break;
				case 'POST' :
					$result['data'] = $this->resource->create($id, $data);
					break;
				case 'PATCH' :
					$result['data'] = $this->resource->patch($id, $data);
					break;
				case 'PUT' :
					$result['data'] = $this->resource->patch($id, $data);
					break;
				case 'DELETE' :
					$result['data'] = $this->resource->delete($id);
					break;
				default :
					$result['error'] = $method . ' method is not allowed';
			}
        } else {
			switch($method){
				case 'GET' :
					// Get the collection object and return the items.
					$result['data'] = $this->resource->fetchAll()->getCurrentItems();
					break;
				case 'POST' :
					$result['data'] = $this->resource->create($data);
					break;
				case 'PATCH' :
					$result['data'] = $this->resource->patchList($data);
					break;
				case 'PUT' :
					$result['data'] = $this->resource->replaceList($data);
					break;
				case 'DELETE' :
					$result['data'] = $this->resource->deleteList($data);
				default :
					$result['error'] = $method . ' method is not allowed';
			}
        }
        $response = new JsonResponse($result, 200);
        return (isset($result['error'])) 
               ? $response->withStatus(StatusCode::STATUS_METHOD_NOT_ALLOWED) : $response;
    }
}
