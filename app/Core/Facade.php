<?php


namespace App\Core;

use RuntimeException;

/**
 * Provides a static interface to classes bound in the container.
 *
 * @see \App\Core\Container
 */
abstract class Facade
{
    /**
     * The container instance.
     *
     * @var Container|null
     */
    protected static ?Container $container = null;

    /**
     * The swapped instances for testing.
     *
     * @var array<string, object>
     */
    protected static array $swappedInstances = [];

    /**
     * Sets the container instance.
     *
     * @param Container $container The container instance.
     * @return void
     */
    public static function setFacadeContainer(Container $container): void
    {
        static::$container = $container;
    }

    /**
     * Swap the facade root with a mock instance.
     *
     * @param string $accessor
     * @param object $instance
     * @return void
     */
    public static function swap(string $accessor, object $instance): void
    {
        static::$swappedInstances[$accessor] = $instance;
    }

    /**
     * Clear all swapped instances.
     *
     * @return void
     */
    public static function clearSwappedInstances(): void
    {
        static::$swappedInstances = [];
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array<int, mixed> $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic(string $method, array $args)
    {
        $accessor = static::getFacadeAccessor();

        if (isset(static::$swappedInstances[$accessor])) {
            $instance = static::$swappedInstances[$accessor];
            return $instance->{$method}(...$args);
        }

        if (static::$container === null) {
            throw new RuntimeException('A facade root has not been set.');
        }

        $instance = static::$container->resolve($accessor);

        return $instance->{$method}(...$args);
    }
}
