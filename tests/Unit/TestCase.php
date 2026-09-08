<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected static bool $eloquentDemarre = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$eloquentDemarre) {
            $capsule = new Capsule();
            $capsule->addConnection([
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            self::$eloquentDemarre = true;
        }
    }
}