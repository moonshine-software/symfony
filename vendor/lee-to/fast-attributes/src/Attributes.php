<?php

declare(strict_types=1);

namespace Leeto\FastAttributes;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

/**
 * @template T
 *
 */
final class Attributes
{
    private bool $withClass = false;

    private ?string $method = null;

    private ?string $property = null;

    private ?string $constant = null;

    private ?string $parameter = null;

    private bool $withMethod = false;

    /** @var array<int, T|ReflectionAttribute<object>> */
    private array $attributes = [];

    private bool $constants = false;

    private bool $methods = false;

    private bool $properties = false;

    private bool $parameters = false;

    /**
     * @param  object|class-string  $class
     * @param  ?class-string<T>  $attribute
     */
    public function __construct(
        private object|string $class,
        private ?string $attribute = null,
    ) {
    }

    /**
     * @param  object|class-string  $class
     * @param  ?class-string<T>  $attribute
     * @return self<T>
     */
    public static function for(object|string $class, ?string $attribute = null): self
    {
        return new self($class, $attribute);
    }

    /**
     * @return self<T>
     */
    public function method(string $value): self
    {
        $this->method = $value;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function property(string $value): self
    {
        $this->property = $value;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function constant(string $value): self
    {
        $this->constant = $value;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function class(): self
    {
        $this->withClass = true;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function constants(): self
    {
        $this->constants = true;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function properties(): self
    {
        $this->properties = true;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function methods(): self
    {
        $this->methods = true;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function parameters(): self
    {
        $this->parameters = true;

        return $this;
    }

    /**
     * @return self<T>
     */
    public function parameter(string $value, bool $withMethod = false): self
    {
        $this->withMethod = $withMethod;
        $this->parameter = $value;

        return $this;
    }

    /**
     * @param  class-string<T>  $attribute
     * @return self<T>
     */
    public function attribute(string $attribute): self
    {
        return new self($this->class, $attribute);
    }

    /**
     * @return array<array-key, T|ReflectionAttribute<object>>
     * @throws ReflectionException
     */
    public function get(): array
    {
        return $this->retrieve();
    }

    /**
     * @param  string|null  $property
     *
     * @return ($property is null ? T|null : mixed)
     * @throws ReflectionException
     */
    public function first(?string $property = null): mixed
    {
        $attributes = $this->get();

        if ($attributes === []) {
            return null;
        }

        return $this->retrieveAttribute($attributes[0], $property);
    }

    /**
     * @return array<array-key, T|ReflectionAttribute<object>>
     * @throws ReflectionException
     */
    private function retrieve(): array
    {
        $reflection = new ReflectionClass($this->class);

        $filled = $this->withClass;
        $this->fillAttributes($reflection, $this->withClass);

        if ($this->properties || ! is_null($this->property)) {
            $filled = true;
            foreach ($reflection->getProperties() as $property) {
                $this->fillAttributes(
                    $property,
                    is_null($this->property) || $this->property === $property->getName()
                );
            }
        }

        if ($this->constants || ! is_null($this->constant)) {
            $filled = true;
            foreach ($reflection->getReflectionConstants() as $constant) {
                $this->fillAttributes(
                    $constant,
                    is_null($this->constant) || $this->constant === $constant->getName()
                );
            }
        }

        if ($this->methods || ! is_null($this->method)) {
            $filled = true;
            foreach ($reflection->getMethods() as $method) {
                $this->retrieveMethodOrParametersAttributes($method);
            }
        }

        if($filled === false) {
            $this->fillAttributes($reflection);
        }

        return $this->attributes;
    }

    private function retrieveMethodOrParametersAttributes(ReflectionMethod $method): void
    {
        if (is_null($this->parameter) || $this->withMethod) {
            $this->fillAttributes(
                $method,
                is_null($this->method) || $this->method === $method->getName()
            );
        }

        if ($this->parameters || ! is_null($this->parameter)) {
            foreach ($method->getParameters() as $parameter) {
                $this->fillAttributes(
                    $parameter,
                    is_null($this->parameter) || $this->parameter === $parameter->getName()
                );
            }
        }
    }

    /**
     * @param  ReflectionClass<object>|ReflectionProperty|ReflectionClassConstant|false|ReflectionMethod|ReflectionParameter  $reflection
     * @param  bool  $condition
     * @return void
     */
    private function fillAttributes(mixed $reflection, bool $condition = true): void
    {
        if ($condition) {
            $this->attributes = [
                ...$this->attributes,
                ...$this->retrieveAttributes($reflection),
            ];
        }
    }

    /**
     * @param  ReflectionClass<object>|ReflectionProperty|ReflectionClassConstant|false|ReflectionMethod|ReflectionParameter  $reflection
     * @return list<ReflectionAttribute<object>>
     */
    private function retrieveAttributes(mixed $reflection): array
    {
        if ($reflection === false) {
            return [];
        }

        return $reflection->getAttributes(
            $this->attribute,
            ReflectionAttribute::IS_INSTANCEOF
        );
    }

    /**
     * @param  ReflectionAttribute<object>|T  $attribute
     * @param  string|null  $property
     * @return mixed
     */
    private function retrieveAttribute(mixed $attribute, ?string $property = null): mixed
    {
        if(!$attribute instanceof ReflectionAttribute) {
            return null;
        }

        return is_null($property)
            ? $attribute->newInstance()
            : $attribute->newInstance()->{$property};
    }
}
