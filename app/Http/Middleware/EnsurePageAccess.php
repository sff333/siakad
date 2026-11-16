<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class EnsurePageAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user->can('manage-users') || $request->routeIs('dashboard')) {
            return $next($request);
        }

        $page = match (true) {
            $request->is('absensi/rekap') => 'rekap-presensi',
            $request->is('rombel', 'mapel', 'mapel/*', 'jadwal', 'jadwal/*') => 'kelas',
            default => $request->segment(1),
        };

        if (!$user->canAccessPage($page)) {
            return Inertia::render('Errors/Forbidden')->toResponse($request)->setStatusCode(403);
        }

        return $next($request);
    }
}
