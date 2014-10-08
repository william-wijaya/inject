<?php
namespace Plum\Inject;

final class Env
{
    private $env;

    private function __construct($env)
    {
        $this->env = $env;
    }

    /**
     * Returns a development environment instance
     *
     * @return Env
     */
    public static function development()
    {
        static $d;
        return $d ?: $d = new Env("dev");
    }

    /**
     * Returns a production environment instance
     *
     * @return Env
     */
    public static function production()
    {
        static $p;
        return $p ?: $p = new Env("prod");
    }
} 
