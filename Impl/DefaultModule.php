<?php
namespace Plum\Inject\Impl;

use Plum\Gen\CodeSpace;
use Plum\Inject\Env;
use Plum\Reflect\Reflection;
use Plum\Inject\Provides;
use Plum\Inject\Singleton;

class DefaultModule
{
    private $env;
    private $space;
    private $reflection;

    public function __construct(
        Env $env, CodeSpace $space, Reflection $reflection
    )
    {
        $this->env = $env;
        $this->space = $space;
        $this->reflection = $reflection;
    }

    /** @Provides(Env::class) @Singleton */
    public function provideEnv()
    {
        return $this->env;
    }

    /** @Provides(CodeSpace::class) @Singleton */
    public function provideCodeSpace()
    {
        return $this->space;
    }

    /** @Provides(Reflection::class) @Singleton */
    public function provideReflection()
    {
        return $this->reflection;
    }
} 
