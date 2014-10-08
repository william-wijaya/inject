<?php
namespace Plum\Inject;

/** @Annotation */
class Provides
{
    const ELEMENT = "array";
    const CONSTANT = "const";

    /**
     * The type
     *
     * @var string
     */
    public $value = Provides::CONSTANT;

    /**
     * Whether to overrides if there's previously configured binding
     *
     * @var bool
     */
    public $overrides = false;
} 
