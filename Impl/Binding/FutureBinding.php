<?php
namespace Plum\Inject\Impl\Binding;

use Plum\Inject\Binding;
use Plum\Inject\BindingVisitor;
use Plum\Inject\ConfigurationException;
use Plum\Inject\Impl\BindingVisitor\FutureBindingResolver;

/**
 * Represents deferred binding which will be resolved once all modules is bound
 */
class FutureBinding extends Binding
{
    /**
     * @var Binding|null
     */
    private $resolution;

    /**
     * Returns the binding resolution
     *
     * @return Binding
     */
    public function resolution()
    {
        return $this->resolution;
    }

    /**
     * Sets the resolution binding
     *
     * @param Binding $binding
     */
    public function resolveWith(Binding $binding)
    {
        $this->resolution = $binding;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(BindingVisitor $visitor)
    {
        if ($visitor instanceof FutureBindingResolver) {
            $visitor->visit($this);
        } else {
            $r = $this->resolution();
            if (!$r) throw new ConfigurationException(
                "Missing binding for " . $this->key()
            );

            $r->accept($visitor);
        }
    }
} 
