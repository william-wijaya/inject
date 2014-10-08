<?php
namespace Plum\Inject;

/**
 * Represents injection type
 */
final class Key
{
    private $t;
    private $q;

    private function __construct($t, $q)
    {
        $this->t = $t;
        $this->q = $q;
    }

    /**
     * Returns the type
     *
     * @return string
     */
    public function type()
    {
        return $this->t;
    }

    /**
     * Returns the qualifier
     *
     * @return Qualifier|null
     */
    public function qualifier()
    {
        return $this->q;
    }

    /**
     * Returns the hash code
     *
     * @return string
     */
    public function hashCode()
    {
        return md5(serialize($this));
    }

    /**
     * Returns a human readable representation
     *
     * @return string
     */
    public function __toString()
    {
        $s = $this->type();
        if ($this->qualifier())
            $s .= " ".$this->qualifier();

        return $s;
    }

    /**
     * Whether this key is constant key
     *
     * @return bool
     */
    public function isConstant()
    {
        return $this->t === Provides::CONSTANT;
    }

    /**
     * Whether this key is array key
     *
     * @return bool
     */
    public function isArray()
    {
        return $this->t === Provides::ELEMENT;
    }

    /**
     * Whether this key is type key
     *
     * @return bool
     */
    public function isType()
    {
        return !$this->isConstant() && !$this->isArray();
    }

    /**
     * Returns type key
     *
     * @param string $name
     * @param Qualifier $qualifier
     *
     * @return Key
     */
    public static function ofType($name, Qualifier $qualifier = null)
    {
        $name = ltrim($name, "\\");

        return new Key($name, $qualifier);
    }

    /**
     * Returns constant key
     *
     * @param Qualifier $qualifier
     * @return Key
     */
    public static function ofConstant(Qualifier $qualifier)
    {
        return new Key(Provides::CONSTANT, $qualifier);
    }

    /**
     * Returns array key
     *
     * @param Qualifier $qualifier
     * @return Key
     */
    public static function ofArray(Qualifier $qualifier)
    {
        return new Key(Provides::ELEMENT, $qualifier);
    }
} 
