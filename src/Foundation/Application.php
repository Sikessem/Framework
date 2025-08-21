<?php

declare(strict_types=1);

namespace Sikessem\Foundation;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Application as BaseApplication;
use Illuminate\Http\Request;
use Sikessem\Contracts\Application as ApplicationContract;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class Application extends BaseApplication implements ApplicationContract
{
    public function run(): void
    {
        define('LARAVEL_START', microtime(true));

        $this->runningInConsole() ? $this->processCommand() : $this->processRequest();
    }

    public function processCommand(): void
    {
        $kernel = $this->makeConsoleKernel();

        $status = $kernel->handle(
            $input = new ArgvInput,
            new ConsoleOutput
        );

        $kernel->terminate($input, $status);

        exit($status);
    }

    public function processRequest(): void
    {
        $kernel = $this->makeHttpKernel();

        $response = $kernel->handle(
            $request = Request::capture()
        )->send();

        $kernel->terminate($request, $response);
    }

    public function makeKernel(): ConsoleKernel|HttpKernel
    {
        return $this->runningInConsole() ? $this->makeConsoleKernel() : $this->makeHttpKernel();
    }

    public function makeConsoleKernel(): ConsoleKernel
    {
        /** @var ConsoleKernel */
        $kernel = $this->make(ConsoleKernel::class);

        return $kernel;
    }

    public function makeHttpKernel(): HttpKernel
    {
        /** @var HttpKernel */
        $kernel = $this->make(HttpKernel::class);

        return $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function path($path = '')
    {
        if (empty($this->appPath) && is_dir($this->rootDir('src'))) {
            $this->appPath = $this->rootDir('src');
        }

        return parent::path($path);
    }

    /**
     * {@inheritDoc}
     */
    public function resourcePath($path = '')
    {
        if (is_dir($this->rootDir('res'))) {
            return $this->rootDir('res').($path !== '' ? DIRECTORY_SEPARATOR.$path : '');
        }

        return parent::resourcePath($path);
    }

    /**
     * Get the path to the templates directory.
     */
    public function templatePath(string $path = ''): string
    {
        if (is_dir($this->rootDir('tpl'))) {
            return $this->rootDir('tpl').($path !== '' ? DIRECTORY_SEPARATOR.$path : '');
        }

        return $this->joinPaths($this->basePath('templates'), $path);
    }

    public function rootDir(string $path = ''): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.$path;
    }

    /**
     * {@inheritDoc}
     */
    public function viewPath($path = '')
    {
        /** @var ConfigContract */
        $config = $this['config'];
        /** @var string[] */
        $viewPaths = $config->get('view.paths', [$this->templatePath()]);
        foreach ($viewPaths as $viewPath) {
            if (is_dir($viewPath)) {
                $viewPath = rtrim($viewPath, DIRECTORY_SEPARATOR);

                return $this->joinPaths($viewPath, $path);
            }
        }

        return $this->templatePath($path);
    }
}
