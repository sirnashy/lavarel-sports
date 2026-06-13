<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class InstallController extends Controller
{
    public function show()
    {
        if ($this->isInstalled()) {
            return redirect()->route('home');
        }

        return view('install.index');
    }

    public function install(Request $request)
    {
        if ($this->isInstalled()) {
            return redirect()->route('home');
        }

        $request->validate([
            'app_name' => ['required', 'string', 'max:50'],
            'app_url' => ['required', 'url', 'max:255'],
            'db_host' => ['required', 'string', 'max:100'],
            'db_port' => ['required', 'numeric'],
            'db_database' => ['required', 'string', 'max:100'],
            'db_username' => ['required', 'string', 'max:100'],
            'db_password' => ['nullable', 'string', 'max:100'],
            'admin_name' => ['required', 'string', 'max:100'],
            'admin_email' => ['required', 'email', 'max:150'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $envData = [
            'APP_NAME' => $request->input('app_name'),
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
            'APP_URL' => rtrim($request->input('app_url'), '/'),
            'DB_CONNECTION' => 'mariadb',
            'DB_HOST' => $request->input('db_host'),
            'DB_PORT' => $request->input('db_port'),
            'DB_DATABASE' => $request->input('db_database'),
            'DB_USERNAME' => $request->input('db_username'),
            'DB_PASSWORD' => $request->input('db_password'),
            'SESSION_DRIVER' => 'database',
            'CACHE_DRIVER' => 'file',
            'QUEUE_CONNECTION' => 'database',
            'LOG_CHANNEL' => 'stack',
            'APP_INSTALLED' => 'true',
        ];

        $writeResult = $this->writeEnvironmentFile($envData);
        if (! $writeResult) {
            return back()->withInput()->withErrors(['env' => 'Unable to write the .env file. Ensure permissions are writable or create it manually.']);
        }

        $this->applyRuntimeDatabaseConfig($envData);

        try {
            DB::connection('mysql')->getPdo();
        } catch (Throwable $exception) {
            return back()->withInput()->withErrors(['db' => 'Database connection failed: '.$exception->getMessage()]);
        }

        try {
            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('migrate', ['--force' => true]);
        } catch (Throwable $exception) {
            return back()->withInput()->withErrors(['install' => 'Installation failed while running setup tasks: '.$exception->getMessage()]);
        }

        $this->createAdminUser($request);
        $this->createInstallLock();

        return redirect()->route('install.success');
    }

    public function success()
    {
        if (! $this->isInstalled()) {
            return redirect()->route('install.index');
        }

        return view('install.success');
    }

    protected function createAdminUser(Request $request): void
    {
        if (! User::where('email', $request->input('admin_email'))->exists()) {
            User::create([
                'name' => $request->input('admin_name'),
                'email' => $request->input('admin_email'),
                'password' => Hash::make($request->input('admin_password')),
                'is_admin' => true,
                'is_active' => true,
            ]);
        }
    }

    protected function writeEnvironmentFile(array $values): bool
    {
        $envPath = base_path('.env');
        $examplePath = base_path('.env.example');

        if (! file_exists($envPath) && file_exists($examplePath)) {
            copy($examplePath, $envPath);
        }

        if (! file_exists($envPath)) {
            file_put_contents($envPath, '');
        }

        if (! is_writable($envPath)) {
            return false;
        }

        $content = file_get_contents($envPath) ?: '';

        foreach ($values as $key => $value) {
            $escapedValue = $this->escapeEnvValue($value);
            $pattern = '/^'.preg_quote($key, '/').'=.*/m';

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "$key=$escapedValue", $content);
            } else {
                $content = rtrim($content, "\n")."\n$key=$escapedValue\n";
            }
        }

        return file_put_contents($envPath, $content) !== false;
    }

    protected function escapeEnvValue($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = trim((string) $value);
        if ($value === '' || preg_match('/\s/', $value) || str_contains($value, '"')) {
            $value = '"'.str_replace('"', '\\"', $value).'"';
        }

        return $value;
    }

    protected function applyRuntimeDatabaseConfig(array $values): void
    {
        $connection = [
            'driver' => $values['DB_CONNECTION'],
            'host' => $values['DB_HOST'],
            'port' => $values['DB_PORT'],
            'database' => $values['DB_DATABASE'],
            'username' => $values['DB_USERNAME'],
            'password' => $values['DB_PASSWORD'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ];

        Config::set('app.name', $values['APP_NAME']);
        Config::set('app.url', $values['APP_URL']);
        Config::set('database.default', 'mysql');
        Config::set('database.connections.mysql', $connection);
        Config::set('session.driver', $values['SESSION_DRIVER']);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    protected function createInstallLock(): void
    {
        @file_put_contents(storage_path('installed'), now()->toDateTimeString());
    }

    protected function isInstalled(): bool
    {
        return env('APP_INSTALLED') === 'true' || file_exists(storage_path('installed'));
    }
}
