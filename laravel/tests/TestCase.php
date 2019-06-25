<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * create a factory with all dependencies automatically fulfilled
     *
     * @param  String   $model_class_name   the models class name
     * @return Factory                      the setup factory
     */
    public static function factoryWithSetup($model_class_name, $count = null)
    {
        return factory($model_class_name, $count)->states('with_setup');
    }
}
