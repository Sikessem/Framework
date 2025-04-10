<?php

declare(strict_types=1);

namespace Sikessem\Contracts;

use Closure;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Foundation\Application as BaseApplication;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;

interface Application extends BaseApplication, CachesConfiguration, CachesRoutes, HttpKernelInterface
{
    public function run(): void;

    public function processCommand(): void;

    public function processRequest(): void;

    public function makeKernel(): ConsoleKernel|HttpKernel;

    public function makeConsoleKernel(): ConsoleKernel;

    public function makeHttpKernel(): HttpKernel;

    public function rootDir(string $path = ''): string;

    /**
     * Get the path to the application "app" directory.
     *
     * @param  string  $path
     * @return string
     */
    public function path($path = '');

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version();

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  string[]  $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers);

    /**
     * Register a callback to run after loading the environment.
     *
     * @return void
     */
    public function afterLoadingEnvironment(Closure $callback);

    /**
     * Register a callback to run before a bootstrapper.
     *
     * @param  string  $bootstrapper
     * @return void
     */
    public function beforeBootstrapping($bootstrapper, Closure $callback);

    /**
     * Register a callback to run after a bootstrapper.
     *
     * @param  string  $bootstrapper
     * @return void
     */
    public function afterBootstrapping($bootstrapper, Closure $callback);

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped();

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath);

    /**
     * Set the application directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useAppPath($path);

    /**
     * Get the base path of the Laravel installation.
     *
     * @param  string  $path
     * @return string
     */
    public function basePath($path = '');

    /**
     * Get the path to the bootstrap directory.
     *
     * @param  string  $path
     * @return string
     */
    public function bootstrapPath($path = '');

    /**
     * Set the bootstrap file directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useBootstrapPath($path);

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     * @return string
     */
    public function configPath($path = '');

    /**
     * Set the configuration directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useConfigPath($path);

    /**
     * Get the path to the database directory.
     *
     * @param  string  $path
     * @return string
     */
    public function databasePath($path = '');

    /**
     * Set the database directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useDatabasePath($path);

    /**
     * Get the path to the language files.
     *
     * @param  string  $path
     * @return string
     */
    public function langPath($path = '');

    /**
     * Set the language file directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useLangPath($path);

    /**
     * Get the path to the public / web directory.
     *
     * @param  string  $path
     * @return string
     */
    public function publicPath($path = '');

    /**
     * Set the public / web directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function usePublicPath($path);

    /**
     * Get the path to the storage directory.
     *
     * @param  string  $path
     * @return string
     */
    public function storagePath($path = '');

    /**
     * Set the storage directory.
     *
     * @param  string  $path
     * @return $this
     */
    public function useStoragePath($path);

    /**
     * Get the path to the resources directory.
     *
     * @param  string  $path
     * @return string
     */
    public function resourcePath($path = '');

    /**
     * Get the path to the templates directory.
     */
    public function templatePath(string $path = ''): string;

    /**
     * Get the path to the views directory.
     *
     * This method returns the first configured path in the array of view paths.
     *
     * @param  string  $path
     * @return string
     */
    public function viewPath($path = '');

    /**
     * Join the given paths together.
     *
     * @param  string  $basePath
     * @param  string  $path
     * @return string
     */
    public function joinPaths($basePath, $path = '');

    /**
     * Get the path to the environment file directory.
     *
     * @return string
     */
    public function environmentPath();

    /**
     * Set the directory for the environment file.
     *
     * @param  string  $path
     * @return $this
     */
    public function useEnvironmentPath($path);

    /**
     * Set the environment file to be loaded during bootstrapping.
     *
     * @param  string  $file
     * @return $this
     */
    public function loadEnvironmentFrom($file);

    /**
     * Get the environment file the application is using.
     *
     * @return string
     */
    public function environmentFile();

    /**
     * Get the fully qualified path to the environment file.
     *
     * @return string
     */
    public function environmentFilePath();

    /**
     * Get or check the current application environment.
     *
     * @param  string|mixed[]  ...$environments
     * @return string|bool
     */
    public function environment(...$environments);

    /**
     * Determine if the application is in the local environment.
     *
     * @return bool
     */
    public function isLocal();

    /**
     * Determine if the application is in the production environment.
     *
     * @return bool
     */
    public function isProduction();

    /**
     * Detect the application's current environment.
     *
     * @return string
     */
    public function detectEnvironment(Closure $callback);

    /**
     * Determine if the application is running in the console.
     *
     * @return bool
     */
    public function runningInConsole();

    /**
     * Determine if the application is running unit tests.
     *
     * @return bool
     */
    public function runningUnitTests();

    /**
     * Determine if the application is running with debug mode enabled.
     *
     * @return bool
     */
    public function hasDebugModeEnabled();

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders();

    /**
     * Register a service provider with the application.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  bool  $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $force = false);

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($provider);

    /**
     * Get the registered service provider instances if any exist.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @return mixed[]
     */
    public function getProviders($provider);

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param  string  $provider
     * @return \Illuminate\Support\ServiceProvider
     */
    public function resolveProvider($provider);

    /**
     * Load and boot all of the remaining deferred providers.
     *
     * @return void
     */
    public function loadDeferredProviders();

    /**
     * Load the provider for a deferred service.
     *
     * @param  string  $service
     * @return void
     */
    public function loadDeferredProvider($service);

    /**
     * Register a deferred provider and service.
     *
     * @param  string  $provider
     * @param  string|null  $service
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null);

    /**
     * Resolve the given type from the container.
     *
     * @param  string  $abstract
     * @param  mixed[]  $parameters
     * @return mixed
     */
    public function make($abstract, array $parameters = []);

    /**
     * Determine if the given abstract type has been bound.
     *
     * @param  string  $abstract
     * @return bool
     */
    public function bound($abstract);

    /**
     * Determine if the application has booted.
     *
     * @return bool
     */
    public function isBooted();

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot();

    /**
     * Register a new boot listener.
     *
     * @param  callable  $callback
     * @return void
     */
    public function booting($callback);

    /**
     * Register a new "booted" listener.
     *
     * @param  callable  $callback
     * @return void
     */
    public function booted($callback);

    /**
     * {@inheritdoc}
     */
    public function handle(SymfonyRequest $request, int $type = self::MAIN_REQUEST, bool $catch = true): SymfonyResponse;

    /**
     * Determine if middleware has been disabled for the application.
     *
     * @return bool
     */
    public function shouldSkipMiddleware();

    /**
     * Get the path to the cached services.php file.
     *
     * @return string
     */
    public function getCachedServicesPath();

    /**
     * Get the path to the cached packages.php file.
     *
     * @return string
     */
    public function getCachedPackagesPath();

    /**
     * Determine if the application configuration is cached.
     *
     * @return bool
     */
    public function configurationIsCached();

    /**
     * Get the path to the configuration cache file.
     *
     * @return string
     */
    public function getCachedConfigPath();

    /**
     * Determine if the application routes are cached.
     *
     * @return bool
     */
    public function routesAreCached();

    /**
     * Get the path to the routes cache file.
     *
     * @return string
     */
    public function getCachedRoutesPath();

    /**
     * Determine if the application events are cached.
     *
     * @return bool
     */
    public function eventsAreCached();

    /**
     * Get the path to the events cache file.
     *
     * @return string
     */
    public function getCachedEventsPath();

    /**
     * Add new prefix to list of absolute path prefixes.
     *
     * @param  string  $prefix
     * @return $this
     */
    public function addAbsoluteCachePathPrefix($prefix);

    /**
     * Get an instance of the maintenance mode manager implementation.
     *
     * @return \Illuminate\Contracts\Foundation\MaintenanceMode
     */
    public function maintenanceMode();

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance();

    /**
     * Throw an HttpException with the given data.
     *
     * @param  int  $code
     * @param  string  $message
     * @param  mixed[]  $headers
     * @return never
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function abort($code, $message = '', array $headers = []);

    /**
     * Register a terminating callback with the application.
     *
     * @param  callable|string  $callback
     * @return $this
     */
    public function terminating($callback);

    /**
     * Terminate the application.
     *
     * @return void
     */
    public function terminate();

    /**
     * Get the service providers that have been loaded.
     *
     * @return mixed[]
     */
    public function getLoadedProviders();

    /**
     * Determine if the given service provider is loaded.
     *
     * @return bool
     */
    public function providerIsLoaded(string $provider);

    /**
     * Get the application's deferred services.
     *
     * @return mixed[]
     */
    public function getDeferredServices();

    /**
     * Set the application's deferred services.
     *
     * @param  mixed[]  $services
     * @return void
     */
    public function setDeferredServices(array $services);

    /**
     * Add an array of services to the application's deferred services.
     *
     * @param  mixed[]  $services
     * @return void
     */
    public function addDeferredServices(array $services);

    /**
     * Determine if the given service is a deferred service.
     *
     * @param  string  $service
     * @return bool
     */
    public function isDeferredService($service);

    /**
     * Configure the real-time facade namespace.
     *
     * @param  string  $namespace
     * @return void
     */
    public function provideFacades($namespace);

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public function currentLocale();

    /**
     * Get the current application fallback locale.
     *
     * @return string
     */
    public function getFallbackLocale();

    /**
     * Set the current application locale.
     *
     * @param  string  $locale
     * @return void
     */
    public function setLocale($locale);

    /**
     * Set the current application fallback locale.
     *
     * @param  string  $fallbackLocale
     * @return void
     */
    public function setFallbackLocale($fallbackLocale);

    /**
     * Determine if the application locale is the given locale.
     *
     * @param  string  $locale
     * @return bool
     */
    public function isLocale($locale);

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases();

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush();

    /**
     * Get the application namespace.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getNamespace();
}
