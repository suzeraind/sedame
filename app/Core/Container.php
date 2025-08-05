<?php

namespace App\Core;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
use Exception;

/**
 * Service container for dependency injection
 * 
 * This container allows binding and resolving dependencies automatically
 * using PHP's Reflection API to resolve class dependencies.
 */
class Container
{
    /**
     * Container bindings storage
     * 
     * @var array<string, Closure> Array of key-value pairs where key is binding name and value is resolver closure
     */
    protected array $bindings = [];

    /**
     * Bind a resolver closure to a key
     * 
     * @param string $key The binding key (usually interface or class name)
     * @param Closure $resolver The closure that returns the resolved instance
     * @return void
     */
    public function bind(string $key, Closure $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    /**
     * Resolve a dependency by key or class name
     * 
     * First checks for manual bindings, then tries to resolve automatically
     * using reflection and constructor parameter analysis.
     * 
     * @param string $key The binding key or fully qualified class name
     * @return mixed The resolved instance
     * @throws Exception If class is not instantiable or dependencies cannot be resolved
     */
    public function resolve(string $key): mixed
    {
        if (isset($this->bindings[$key])) {
            $resolver = $this->bindings[$key];
            return $resolver($this);
        }

        $reflectionClass = new ReflectionClass($key);

        if (!$reflectionClass->isInstantiable()) {
            throw new Exception("Class {$key} is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $key;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type || !($type instanceof ReflectionNamedType) || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new Exception("Cannot resolve primitive parameter {$parameter->getName()} in class {$key}");
            }

            $dependencies[] = $this->resolve($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
