<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Enums\Can;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->setupLogViewer();
        $this->configModels();
        $this->configCommands();
        $this->configUrls();
        $this->configDate();
        $this->configGates();
    }

    private function setupLogViewer(): void
    {
        LogViewer::auth(fn ($request): true => true);
    }

    private function configModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict();
    }

    private function configCommands(): void
    {
        DB::prohibitDestructiveCommands(
            app()->isProduction()
        );
    }

    private function configUrls(): void
    {
        URL::forceHttps();
    }

    private function configDate(): void
    {
        Date::use(CarbonImmutable::class);
    }

    private function configGates(): void
    {
        foreach (Can::cases() as $permission) {
            Gate::define(
                $permission->value,
                fn ($user): bool => $user->permissions()
                    ->where('name', $permission->value)
                    ->exists()
            );
        }
    }
}
