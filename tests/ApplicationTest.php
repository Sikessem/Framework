<?php

namespace Sikessem\Tests\Unit;

use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Sikessem\Application as ConcreteApplication;
use Sikessem\Contracts\Application as ApplicationContract;

beforeEach(function () {
    $app = ConcreteApplication::configure()->create();

    $app->singleton(
        ConsoleKernelContract::class,
        ConsoleKernel::class
    );

    $this->app = $app;
});

it('should implement application contract', function () {
    expect($this->app)->toBeInstanceOf(ApplicationContract::class);
});

it('should be an instance of the Laravel application', function () {
    expect($this->app)->toBeInstanceOf(Application::class);
});

it('should make kernel', function () {
    expect($this->app->makeKernel())->toBeInstanceOf(ConsoleKernelContract::class);
});
