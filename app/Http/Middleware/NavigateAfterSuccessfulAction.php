<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class NavigateAfterSuccessfulAction
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->header('X-Inertia') || $request->isMethodSafe()
            || !$response instanceof RedirectResponse
            || !in_array('success', $request->session()->get('_flash.new', []), true)) {
            return $response;
        }

        $target = $response->getTargetUrl();
        $referer = $request->headers->get('referer');
        if ($referer && $target !== $referer && str_starts_with($target, $request->getSchemeAndHttpHost().'/')) {
            return Inertia::location($response);
        }

        return $response;
    }
}
