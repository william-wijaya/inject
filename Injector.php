<?php
namespace Plum\Inject;

use Plum\Gen\CodeSpace;
use Plum\Inject\Impl\Bindings;
use Plum\Inject\Impl\DefaultModule;
use Plum\Inject\Impl\FactoryFactory;
use Plum\Inject\Impl\Graph\BindingsGraphImpl;
use Plum\Inject\Impl\Graph\LazyGraph;
use Plum\Inject\Impl\InjectorImpl;
use Plum\Inject\Impl\Scoping;
use Plum\Reflect\Reflection;

abstract class Injector
{
    /**
     * Returns binding of given {@link Key}
     *
     * @param Key $key
     * @return Binding
     */
    public abstract function getBinding(Key $key);

    /**
     * Returns instance of given {@link Key}
     *
     * @param Key $key
     * @return mixed
     */
    public abstract function get(Key $key);

    /**
     * Returns instance of given type
     *
     * @param string $type
     * @return mixed
     */
    public function getInstance($type)
    {
        return $this->get(Key::ofType($type));
    }

    /**
     * Creates child injector which inherits all bindings from this injector
     *
     * @param Scope $scope
     * @param string|object,... $modules
     *
     * @return Injector
     */
    public abstract function fork(Scope $scope, ...$modules);

    /**
     * Creates an {@link Injector}
     *
     * @param Env|null $env the environment, default to development env
     * @param string|null $path the code space path, default to system temp dir
     * @param string|object,... $modules
     *
     * @return Injector
     */
    public static function create(Env $env = null, $path = null, ...$modules)
    {
        $env = $env ?: Env::development();
        $path = $path ?: sys_get_temp_dir();

        $r = Reflection::create();
        $cs = new CodeSpace($path);

        $modules[] = new DefaultModule($env, $cs, $r);

        $b = new Bindings();
        $s = new Scoping(new Singleton(), $modules);

        if ($env === Env::production()) {
            $g = new LazyGraph($b, $r, $s);
        } else {
            $g = BindingsGraphImpl::create($b, $r, $s);

            $cs->clear();
        }

        $f = new FactoryFactory($cs, $g, $s);

        return new InjectorImpl($env, $cs, $s, $g, $f, $r);
    }
} 
