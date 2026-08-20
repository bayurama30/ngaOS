<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request as SfRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'post' => Post::class,
            'user' => User::class,
        ]);

        // Tailscale Funnel terminates TLS at the edge (HTTPS) then proxies
        // plain HTTP to localhost, mounted under the /ngaos sub-path. Force
        // the root URL (incl. sub-path) and https scheme so route(), asset()
        // and @vite() all generate correct /ngaos/... URLs.
        Request::setTrustedProxies(
            ['127.0.0.1', '100.64.0.0/10', '192.168.0.0/16'],
            63
        );
        URL::forceRootUrl('https://web.tail625365.ts.net/ngaos');
        URL::forceScheme('https');
    }
}
