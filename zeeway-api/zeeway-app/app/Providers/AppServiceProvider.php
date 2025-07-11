<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // User
        $this->app->bind(\App\Repositories\UserRepositoryInterface::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Services\UserServiceInterface::class, \App\Services\UserService::class);
        // Task
        $this->app->bind(\App\Repositories\TaskRepositoryInterface::class, \App\Repositories\TaskRepository::class);
        $this->app->bind(\App\Services\TaskServiceInterface::class, \App\Services\TaskService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
