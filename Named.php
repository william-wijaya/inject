<?php
namespace Plum\Inject;

/** @Annotation */
final class Named implements Qualifier
{
    /**
     * The name
     *
     * @var string
     */
    public $value;

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return '@Named("'.$this->value.'")';
    }

    public static function name($name)
    {
        $n = new Named();
        $n->value = $name;

        return $n;
    }
} 
