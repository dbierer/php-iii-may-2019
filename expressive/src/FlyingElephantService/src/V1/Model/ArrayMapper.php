<?php
/**
 * Array Mapper Class
 */

namespace FlyingElephantService\V1\Model;

use DomainException;
use FlyingElephantService\V1\Rest\PropulsionSystems\{PropulsionSystemsCollection as Collection, PropulsionSystemsEntity as Entity};
use InvalidArgumentException;
use Traversable;
use Ramsey\Uuid\Uuid;
use Zend\Stdlib\ArrayUtils;
use ZF\Configuration\ConfigResource;
use Zend\Diactoros\Response\JsonResponse;
use Fig\Http\Message\StatusCodeInterface as StatusCode;
use Zend\Hydrator\ObjectPropertyHydrator;

/**
 * Mapper implementation using a file returning PHP arrays
 */
class ArrayMapper implements MapperInterface
{
	const ERROR_INVALID_ID = 'ERROR: invalid identifier';
	
    /**
     * @var ConfigResource
     */
    protected $configResource;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var Entity
     */
    protected $entityPrototype;

    /**
     * @var ObjectPropertyHydrator
     */
    protected $hydrator;

    /**
     * @param array $data
     * @param ConfigResource $configResource
     */
    public function __construct(array $data, ConfigResource $configResource)
    {
        $this->data = $data;
        $this->configResource = $configResource;

        $this->hydrator = new ObjectPropertyHydrator();
        $this->entityPrototype = new Entity;
    }

    /**
     * @param array|Traversable|\stdClass $data 
     * @return Entity
     */
    public function create($data)
    {
        if ($data instanceof Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }

        if (is_object($data)) {
            $data = (array) $data;
        }

        if (!is_array($data)) {
			return new JsonResponse([
				'error' => sprintf('Invalid data provided to %s; must be an array or Traversable', __METHOD__)],
				StatusCode::STATUS_NOT_ACCEPTABLE);
        }

        $id         = Uuid::uuid4()->toString();
        $data['id'] = $id;

        // This is not implemented at this time
        if (! isset($data['timestamp']) || ! $data['timestamp']) {
            $data['timestamp'] = time();
        }

        $this->data[$id] = $data;
        $this->persistData();

        return $this->createEntity($data);
    }

    /**
     * @param string $id 
     * @return Entity
     */
    public function fetch($id)
    {
        if (!Uuid::isValid($id)) {
			return new JsonResponse(['error' => self::ERROR_INVALID_ID], StatusCode::STATUS_NOT_ACCEPTABLE);
        }

        if (!array_key_exists($id, $this->data)) {
			return new JsonResponse(['error' => 'Status message not found'], StatusCode::STATUS_NOT_ACCEPTABLE);
        }
        return $this->createEntity($this->data[$id]);
    }

    /**
     * @return Collection
     */
    public function fetchAll()
    {
        return new Collection($this->createCollection());
    }

    /**
     * @param string $id 
     * @param array|Traversable|\stdClass $data 
     * @return Entity
     */
    public function update($id, $data)
    {
        if (!Uuid::isValid($id)) {
			return new JsonResponse(['error' => self::ERROR_INVALID_ID], StatusCode::STATUS_NOT_ACCEPTABLE);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }

        if (! array_key_exists($id, $this->data)) {
			return new JsonResponse(['error' => 'Cannot update; no such status message'], StatusCode::STATUS_NOT_ACCEPTABLE);
        }

        $updated = ArrayUtils::merge($this->data[$id], $data);
        $updated['timestamp'] = time();
        $this->data[$id] = $updated;
        $this->persistData();

        return $this->createEntity($updated);
    }

    /**
     * @param string $id 
     * @return bool
     */
    public function delete($id)
    {
        if (!Uuid::isValid($id)) {
			return new JsonResponse(['error' => self::ERROR_INVALID_ID], StatusCode::STATUS_NOT_ACCEPTABLE);
        }

        if (! array_key_exists($id, $this->data)) {
			return new JsonResponse(['error' => 'Cannot delete; no such status message'], StatusCode::STATUS_NOT_ACCEPTABLE);
        }

        unset($this->data[$id]);
        $this->persistData();

        return true;
    }

    /**
     * @param array $item 
     * @return Entity
     */
    protected function createEntity(array $item)
    {
        return $this->hydrator->hydrate($item, $this->entityPrototype);
    }

    /**
     * @return HydratingArrayPaginator
     */
    protected function createCollection()
    {
        return new HydratingArrayPaginator($this->data, $this->hydrator, $this->entityPrototype);
    }

    /**
     *
     */
    protected function persistData()
    {
        $this->configResource->overWrite($this->data);
    }
}
