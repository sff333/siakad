<?php

namespace App\Http\Middleware;

use App\Models\LogAktivitas;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogPerubahanData
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true) || !$request->user()) {
            return $next($request);
        }

        // Capture the actor before an update can change their name or role.
        $actor = clone $request->user();
        $response = $next($request);
        $session = $request->session();

        // Mutation controllers flash a success message after persisting their changes.
        if ($response->getStatusCode() < 400 && in_array('success', $session->get('_flash.new', []), true)) {
            LogAktivitas::catat($actor, $session->get('success'), $request->getPathInfo());
        }

        return $response;
    }
}
