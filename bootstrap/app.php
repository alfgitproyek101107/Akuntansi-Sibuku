<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('app:process-recurring-templates')->daily();
        $schedule->command('accounts:unlock-expired')->everyFiveMinutes();
        $schedule->command('demo:reset --auto')->dailyAt('02:00'); // Auto reset demo data daily at 2 AM

        // Approval system tasks
        $schedule->command('approvals:process-auto-approvals')->hourly(); // Check for auto-approvals
        $schedule->command('approvals:process-expired')->daily(); // Process expired approvals

        // WhatsApp automated reports
        $schedule->command('whatsapp-reports:send-scheduled')->everyMinute(); // Check every minute for due reports
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'branch.isolation' => \App\Http\Middleware\BranchIsolation::class,
            'session.management' => \App\Http\Middleware\SessionManagement::class,
            'demo.restrictions' => \App\Http\Middleware\EnsureDemoRestrictions::class,
            'demo.protect' => \App\Http\Middleware\DemoModeProtection::class,
        ]);

        // Apply session management and branch isolation to web routes (except auth)
        $middleware->web(append: [
            \App\Http\Middleware\SessionManagement::class,
            \App\Http\Middleware\BranchIsolation::class,
            \App\Http\Middleware\EnsureDemoRestrictions::class,
            \App\Http\Middleware\DemoModeProtection::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        \App\Providers\ActivityLogServiceProvider::class,
    ])
    ->create();
