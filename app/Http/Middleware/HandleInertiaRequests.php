<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                    'role_label' => User::ROLES[$request->user()->role] ?? $request->user()->role,
                ] : null,
                'can_manage_users' => $request->user()?->can('manage-users') ?? false,
                'allowed_pages' => $request->user()?->allowedPages() ?? [],
                'page_labels' => User::PAGES,
            ],
            'flash' => Inertia::always(fn () => [
                'id' => $request->session()->hasAny(['success', 'error']) ? (string) Str::uuid() : null,
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ]),
            'profil_sekolah' => fn () => \App\Models\ProfilSekolah::first(),
        ];
    }
}
