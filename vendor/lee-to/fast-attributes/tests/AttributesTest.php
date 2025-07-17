<?php

declare(strict_types=1);

namespace Leeto\FastAttributes\Tests;

use JsonException;
use Leeto\FastAttributes\Attributes;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\ClassAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\ConstantAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\MethodAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\ParameterAttribute;
use Leeto\FastAttributes\Tests\Fixtures\Attributes\PropertyAttribute;
use Leeto\FastAttributes\Tests\Fixtures\ClassWithAttributes;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionException;

final class AttributesTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    #[Test]
    public function classAttributes(): void
    {
        // All class attributes

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->class()
            ->get();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(ClassAttribute::class, $attributes[0]->newInstance());

        // All class attributes without scope

        $attributes = Attributes::for(ClassWithAttributes::class)->get();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(ClassAttribute::class, $attributes[0]->newInstance());

        // Concrete class attribute

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->class()
            ->get();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(ClassAttribute::class, $attributes[0]->newInstance());

        // First class attribute

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->class()
            ->first();

        $this->assertInstanceOf(ClassAttribute::class, $attribute);

        // First class attribute property

        $value = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->class()
            ->first('variable');

        $this->assertEquals('some value', $value);

        // Not found

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(PropertyAttribute::class)
            ->get();

        $this->assertEmpty($attributes);

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(PropertyAttribute::class)
            ->first();

        $this->assertNull($attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function propertyAttributes(): void
    {
        // All property attributes

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(PropertyAttribute::class)
            ->property('variable')
            ->get();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(PropertyAttribute::class, $attributes[0]->newInstance());

        // First property attribute

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(PropertyAttribute::class)
            ->property('variable')
            ->first();

        $this->assertInstanceOf(PropertyAttribute::class, $attribute);

        // First property attribute property

        $value = Attributes::for(ClassWithAttributes::class)
            ->attribute(PropertyAttribute::class)
            ->property('variable')
            ->first('variable');

        $this->assertEquals('some value', $value);

        // Not found

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->property('variable')
            ->get();

        $this->assertEmpty($attributes);

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->property('variable')
            ->first();

        $this->assertNull($attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function constantAttributes(): void
    {
        // All constant attributes

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(ConstantAttribute::class)
            ->constant('VARIABLE')
            ->get();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(ConstantAttribute::class, $attributes[0]->newInstance());

        // First constant attribute

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(ConstantAttribute::class)
            ->constant('VARIABLE')
            ->first();

        $this->assertInstanceOf(ConstantAttribute::class, $attribute);

        // First constant attribute property

        $value = Attributes::for(ClassWithAttributes::class)
            ->attribute(ConstantAttribute::class)
            ->constant('VARIABLE')
            ->first('variable');

        $this->assertEquals('some value', $value);

        // Not found

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->constant('VARIABLE')
            ->get();

        $this->assertEmpty($attributes);

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->constant('VARIABLE')
            ->first();

        $this->assertNull($attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function methodAttributes(): void
    {
        // All method attributes

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(MethodAttribute::class)
            ->method('someMethod')
            ->get();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(MethodAttribute::class, $attributes[0]->newInstance());

        // First method attribute

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(MethodAttribute::class)
            ->method('someMethod')
            ->first();

        $this->assertInstanceOf(MethodAttribute::class, $attribute);

        // First method attribute property

        $value = Attributes::for(ClassWithAttributes::class)
            ->attribute(MethodAttribute::class)
            ->method('someMethod')
            ->first('variable');

        $this->assertEquals('some value', $value);

        // Not found

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->method('someMethod')
            ->get();

        $this->assertEmpty($attributes);

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->method('someMethod')
            ->first();

        $this->assertNull($attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function parameterAttributes(): void
    {
        // All parameter attributes

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(ParameterAttribute::class)
            ->method('someMethod')
            ->parameter('variable')
            ->get();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(ParameterAttribute::class, $attributes[0]->newInstance());

        // First parameter attribute

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(ParameterAttribute::class)
            ->method('someMethod')
            ->parameter('variable')
            ->first();

        $this->assertInstanceOf(ParameterAttribute::class, $attribute);

        // First parameter attribute property

        $value = Attributes::for(ClassWithAttributes::class)
            ->attribute(ParameterAttribute::class)
            ->method('someMethod')
            ->parameter('variable')
            ->first('variable');

        $this->assertEquals('some value', $value);

        // Not found

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->method('someMethod')
            ->parameter('variable')
            ->get();

        $this->assertEmpty($attributes);

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->attribute(ClassAttribute::class)
            ->method('someMethod')
            ->parameter('variable')
            ->first();

        $this->assertNull($attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function simpleAttributes(): void
    {
        $attributes = Attributes::for(ClassWithAttributes::class)
            ->property('variable')
            ->constant('VARIABLE')
            ->method('someMethod')
            ->parameter('variable')
            ->get();

        $this->assertCount(3, $attributes);

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->property('variable')
            ->constant('VARIABLE')
            ->method('someMethod')
            ->parameter('variable')
            ->class()
            ->get();

        $this->assertCount(4, $attributes);

        $attributes = Attributes::for(ClassWithAttributes::class)
            ->class()
            ->property('variable')
            ->constant('VARIABLE')
            ->method('someMethod')
            ->parameter('variable', withMethod: true)
            ->get();

        $this->assertCount(5, $attributes);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function arrayOfConstantsAttributes(): void
    {
        $attributes = Attributes::for(ClassWithAttributes::class)
            ->constants()
            ->get();

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->constants()
            ->first();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(ConstantAttribute::class, $attributes[0]->newInstance());
        $this->assertInstanceOf(ConstantAttribute::class, $attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function arrayOfPropertiesAttributes(): void
    {
        $attributes = Attributes::for(ClassWithAttributes::class)
            ->properties()
            ->get();

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->properties()
            ->first();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(PropertyAttribute::class, $attributes[0]->newInstance());
        $this->assertInstanceOf(PropertyAttribute::class, $attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function arrayOfMethodsAttributes(): void
    {
        $attributes = Attributes::for(ClassWithAttributes::class)
            ->methods()
            ->get();

        $attribute = Attributes::for(ClassWithAttributes::class)
            ->methods()
            ->first();

        $this->assertCount(1, $attributes);
        $this->assertInstanceOf(MethodAttribute::class, $attributes[0]->newInstance());
        $this->assertInstanceOf(MethodAttribute::class, $attribute);
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function arrayOfParametersAttributes(): void
    {
        $attributes = Attributes::for(ClassWithAttributes::class)
            ->methods()
            ->parameters()
            ->get();

        $this->assertCount(2, $attributes);
    }
}
