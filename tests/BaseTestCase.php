<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /** 
     * Application
     * 
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->createApplication();

        $traits = array_flip(class_uses_recursive(static::class));
        if (isset($traits[UseDatabaseTrait::class])) {
            $this->runMigration();
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $traits = array_flip(class_uses_recursive(static::class));
        if (isset($traits[UseDatabaseTrait::class])) {
            $this->rollbackMigration();
        }
        unset($this->app);
        parent::tearDown();
    }

    protected function createApplication()
    {
        $settings = require __DIR__ . '/../app/settings.php';

        $container = new \Pimple\Container(['settings'=> $settings]);

        $container->register(new \App\Services\EloquentServiceProvider);

        \App\Facades\Facade::setFacadeContainer($container);

        $this->container = $container;
    }
}