<?php

namespace Wyrfel\PhpCallback;

use PHPUnit\Framework\TestCase;
use Exception;

class CallbackTest extends TestCase
{
    const RETURN_VALUE = 'abcd';

    public function testInvocationArgumentsArePrependedByDefault(): void
    {
        $object = $this->getMockBuilder(Callback::class)
            ->setMethods(['__invoke'])
            ->disableOriginalConstructor()
            ->getMock();

        $object->expects($this->any())
            ->method('__invoke')
            ->with('a', 'b', 'c', 'd')
            ->will($this->returnValue(self::RETURN_VALUE));

        $callback = new Callback([$object, '__invoke'], 'c', 'd');

        $this->assertSame(self::RETURN_VALUE, $callback('a', 'b'));
    }

    public function testPrependInvokationArguments(): void
    {
        $object = $this->getMockCallback();

        $object->expects($this->any())
            ->method('__invoke')
            ->with('a', 'b', 'c', 'd')
            ->will($this->returnValue(self::RETURN_VALUE));

        $callback = new Callback([$object, '__invoke'], 'c', 'd');
        $this->assertSame($callback, $callback->prependInvocationArguments());

        $this->assertSame(self::RETURN_VALUE, $callback('a', 'b'));
    }

    public function testAppendInvocationArguments(): void
    {
        $object = $this->getMockCallback();

        $object->expects($this->any())
            ->method('__invoke')
            ->with('c', 'd', 'a', 'b')
            ->will($this->returnValue(self::RETURN_VALUE));

        $callback = new Callback([$object, '__invoke'], 'c', 'd');
        $this->assertSame($callback, $callback->appendInvocationArguments());

        $this->assertSame(self::RETURN_VALUE, $callback('a', 'b'));
    }

    public function testIgnoreInvocationArguments(): void
    {
        $object = $this->getMockCallback();

        $object->expects($this->any())
            ->method('__invoke')
            ->with('c', 'd')
            ->will($this->returnValue(self::RETURN_VALUE));

        $callback = new Callback([$object, '__invoke'], 'c', 'd');
        $this->assertSame($callback, $callback->ignoreInvocationArguments());

        $this->assertSame(self::RETURN_VALUE, $callback('a', 'b'));
    }

    public function testOverrideInstantiationArguments(): void
    {
        $object = $this->getMockCallback();

        $object->expects($this->any())
            ->method('__invoke')
            ->with('a', 'd')
            ->will($this->returnValue(self::RETURN_VALUE));

        $callback = new Callback([$object, '__invoke'], 'c', 'd');
        $this->assertSame($callback, $callback->overrideInstantiationArguments());

        $this->assertSame(self::RETURN_VALUE, $callback('a'));
    }

    public function testEmptyInvocationArguments(): void
    {
        $object = $this->getMockCallback();

        $object->expects($this->any())
            ->method('__invoke')
            ->with('c', 'd')
            ->will($this->returnValue(self::RETURN_VALUE));

        $callback = new Callback([$object, '__invoke'], 'c', 'd');

        $this->assertSame(self::RETURN_VALUE, $callback());
    }

    public function testEmptyInstantiationArguments(): void
    {
        $object = $this->getMockCallback();

        $object->expects($this->any())
            ->method('__invoke')
            ->with('a', 'b')
            ->will($this->returnValue(self::RETURN_VALUE));

        $callback = new Callback([$object, '__invoke']);

        $this->assertSame(self::RETURN_VALUE, $callback('a', 'b'));
    }

    protected function getMockCallback(): Callback
    {
        return $this->getMockBuilder(Callback::class)
            ->setMethods(['__invoke'])
            ->disableOriginalConstructor()
            ->getMock();
    }
}
