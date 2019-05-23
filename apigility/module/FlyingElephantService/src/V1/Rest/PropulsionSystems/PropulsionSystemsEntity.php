<?php
/**
 * Propulsion Systems Entity Class
 */
namespace FlyingElephantService\V1\Rest\PropulsionSystems;

class PropulsionSystemsEntity
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $propellant;

    /**
     * @var int
     */
    public $timestamp;
}
