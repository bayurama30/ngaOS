<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use ReflectionClass;

/**
 * Local-development mirror of the production Funnel mount.
 *
 * Production is served behind a Tailscale Funnel reverse proxy that mounts the
 * app under the `/ngaos` sub-path. The Blade/Alpine views therefore hardcode
 * fetch()/href URLs beginning with `/ngaos`. On localhost (`php artisan serve`)
 * there is no edge proxy to mount that sub-path, so `/ngaos/*` requests 404 and
 * the chatbot (and every API-driven widget) fails with a frontend
 * "Maaf, terjadi kesalahan. Silakan coba lagi." — which is why it leaves no
 * trace in Laravel's log (it never reaches the application).
 *
 * This global middleware runs BEFORE routing and strips the leading `/ngaos`
 * from the request URI so localhost behaves exactly like the Funnel.
 *
 * It is a no-op everywhere except APP_ENV=local, so production (where the proxy
 * strips `/ngaos`) is untouched. Asset URLs are `/build/*` locally (the
 * forceRootUrl override is gated off in AppServiceProvider), so they are never
 * affected by this rewrite.
 */
class StripNgaosPrefix
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('local') && str_starts_with($request->getRequestUri(), '/ngaos')) {
            $this->rewriteUri($request);
        }

        return $next($request);
    }

    protected function rewriteUri(Request $request): void
    {
        // `/ngaos` is exactly 6 bytes.
        $stripped = substr($request->getRequestUri(), 6);

        if ($stripped === '' || $stripped === '/' || $stripped === '?') {
            $stripped = '/';
        } elseif (str_starts_with($stripped, '?')) {
            $stripped = '/' . ltrim($stripped, '?');
        } elseif (! str_starts_with($stripped, '/')) {
            $stripped = '/' . $stripped;
        }

        $request->server->set('REQUEST_URI', $stripped);
        $request->server->set('UNENCODED_URL', $stripped);

        // Symfony caches requestUri/pathInfo on first access (and the router
        // may already have read them). Force a recompute by nulling the cached
        // pathInfo and pointing requestUri at the rewritten value.
        try {
            $ref = new ReflectionClass($request);
            foreach (['requestUri', 'pathInfo'] as $name) {
                $prop = $ref->getProperty($name);
                $prop->setAccessible(true);
                $prop->setValue($request, $name === 'requestUri' ? $stripped : null);
            }
        } catch (\Throwable) {
            // Server vars were already updated above; fall through best-effort.
        }
    }
}
