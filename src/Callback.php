<?php

namespace Wyrfel\PhpCallback;

/**
 * Callback helper
 *
 * This allows passing parameters to callbacks for php built-ins that take a callback
 * parameter without providing the option to specify additional arguments.
 *
 * The invocation arguments can be prepended (default) or appended to the
 * instantiation arguments, they can be ignored altogether or
 * override the instantiation arguments
 */
class Callback
{
    const PREPEND_INVOCATION_ARGS = 1;
    const APPEND_INVOCATION_ARGS = 2;
    const IGNORE_INVOCATION_ARGS = 3;
    const OVERRIDE_INSTANTIATION_ARGS = 4;

    /**
     * @var callable - the php callable
     */
    protected $callable;

    /**
     * @var array - the arguments
     */
    protected $arguments;

    /**
     * @var int - the argument merge behaviour
     */
    protected $mode = self::PREPEND_INVOCATION_ARGS;

    /**
     * internally stores the object, method name and arguments given
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
        $this->arguments = array_slice(func_get_args(), 1);
    }

    /**
     * invokes the method on the object with the given arguments
     */
    public function __invoke()
    {
        $arguments = $this->mergeArguments(func_get_args());

        return call_user_func_array($this->callable, $arguments);
    }

    /**
     * sets the invocation behaviour to append invocation arguments
     * to the instantiation arguments
     */
    public function appendInvocationArguments(): Callback
    {
        $this->mode = self::APPEND_INVOCATION_ARGS;

        return $this;
    }

    /**
     * sets the invocation behaviour to ignore invocation arguments alltogether
     */
    public function ignoreInvocationArguments(): Callback
    {
        $this->mode = self::IGNORE_INVOCATION_ARGS;

        return $this;
    }

    /**
     * sets the invocation behaviour to prepend invocation arguments
     * to the instantiation arguments
     */
    public function prependInvocationArguments(): Callback
    {
        $this->mode = self::PREPEND_INVOCATION_ARGS;

        return $this;
    }

    /**
     * sets the invocation behaviour to override instantiation arguments
     * with invocation arguments based on their position
     *
     * (the usefulness of this is questionable)
     */
    public function overrideInstantiationArguments(): Callback
    {
        $this->mode = self::OVERRIDE_INSTANTIATION_ARGS;

        return $this;
    }

    /**
     * merges the invocation arguments with the instantiation arguments
     * according to the set mode
     */
    protected function mergeArguments(array $invocationArguments): array
    {
        if ($this->mode === self::IGNORE_INVOCATION_ARGS) {
            return $this->arguments;
        }

        if ($this->mode === self::APPEND_INVOCATION_ARGS) {
            return array_merge($this->arguments, $invocationArguments);
        }

        if ($this->mode === self::OVERRIDE_INSTANTIATION_ARGS) {
            $tmp = $this->arguments;

            foreach ($invocationArguments as $k => $v) {
                $tmp[$k] = $v;
            }

            return $tmp;
        }

        return array_merge($invocationArguments, $this->arguments);
    }
}
