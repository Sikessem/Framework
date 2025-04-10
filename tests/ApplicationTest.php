<?php

namespace Sikessem\Tests\Unit;

use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Sikessem\Application;
use Sikessem\Contracts\IsApplication;

beforeEach(function () {
    $app = Application::configure()->create();

    $app->singleton(
        ConsoleKernelContract::class,
        ConsoleKernel::class
    );

    $this->app = $app;
});

it('should implement application contract', function () {
    expect($this->app)->toBeInstanceOf(IsApplication::class);
});

it('should be an instance of the Laravel application', function () {
    expect($this->app)->toBeInstanceOf(BaseApplication::class);
});

it('should make kernel', function () {
    expect($this->app->makeKernel())->toBeInstanceOf(ConsoleKernelContract::class);
});
