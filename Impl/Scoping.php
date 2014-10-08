<?php
namespace Plum\Inject\Impl;

use Plum\Inject\Scope;

class Scoping
{
    private $hash;
    private $scope;
    private $modules;

    public function __construct(Scope $scope, array $modules)
    {
        $this->scope = $scope;
        $this->modules = $modules;

        $s = get_class($this->scope);
        foreach ($modules as $m) {
            if (is_object($m))
                $s .= get_class($m);
            else
                $s .= $m;
        }
        $this->hash = md5($s);
    }

    /**
     * Returns the scope instance
     *
     * @return Scope
     */
    public function scope()
    {
        return $this->scope;
    }

    /**
     * Returns the modules
     *
     * @return array
     */
    public function modules()
    {
        return $this->modules;
    }

    /**
     * Returns unique hash code
     *
     * @return string
     */
    public function hashCode()
    {
        return $this->hash;
    }
} 
