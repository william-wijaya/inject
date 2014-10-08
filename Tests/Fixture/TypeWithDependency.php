<?php
namespace Plum\Inject\Tests\Fixture;

class TypeWithDependency
{
    private $d;

    public function __construct(Dependency $d)
    {
        $this->d = $d;
    }

    public function dependency()
    {
        return $this->d;
    }
} 
