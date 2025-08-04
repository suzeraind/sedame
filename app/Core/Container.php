<?php

namespace App\Core;

use Closure;

class Container
{
    protected array $bindings = [];

    public function bind(string $key, Closure $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve(string $key): mixed
    {
        if (isset($this->bindings[$key])) {
            $resolver = $this->bindings[$key];
            return $resolver($this);
        }

        $reflectionClass = new \ReflectionClass($key);

        if (!$reflectionClass->isInstantiable()) {
            throw new \Exception("Class {$key} is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $key;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new \Exception("Cannot resolve primitive parameter {$parameter->getName()} in class {$key}");
            }

            $dependencies[] = $this->resolve($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
