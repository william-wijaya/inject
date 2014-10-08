<?php
namespace Plum\Inject\Impl\Binding;

use Plum\Inject\Binding\ConstBinding;
use Plum\Inject\BindingVisitor;
use Plum\Inject\ConfigurationException;
use Plum\Inject\Key;

class OptionalBinding extends FutureBinding
{
    private $const;

    public function __construct(Key $key, ConstBinding $const)
    {
        parent::__construct($key);

        $this->const = $const;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(BindingVisitor $visitor)
    {
        try {
            parent::accept($visitor);
        } catch (ConfigurationException $e) {
            $this->const->accept($visitor);
        }
    }
} 
