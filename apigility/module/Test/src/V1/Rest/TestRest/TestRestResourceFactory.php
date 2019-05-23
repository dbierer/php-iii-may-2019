<?php
namespace Test\V1\Rest\TestRest;

class TestRestResourceFactory
{
    public function __invoke($services)
    {
        return new TestRestResource();
    }
}
