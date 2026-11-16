<?php

namespace App\Http\Controllers;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Pengguna/Index', [
            'users' => User::query()->select('id', 'name', 'email', 'role', 'is_active')->orderBy('name')->orderBy('id')->paginate(20),
            'roles' => User::ROLES,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Pengguna/Form', ['roles' => User::ROLES, 'pages' => User::PAGES]);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validated($request));

        return to_route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Pengguna/Form', [
            'account' => $user->only('id', 'name', 'email', 'role') + ['page_permissions' => $user->allowedPages()],
            'roles' => User::ROLES,
            'pages' => User::PAGES,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);
        $actorId = $request->user()->getKey();

        DB::transaction(function () use ($actorId, $user, $data): void {
            $account = $this->lockAccountForManagement($actorId, $user);

            if ($actorId === $account->getKey() && $data['role'] !== $account->role) {
                throw ValidationException::withMessages(['role' => 'Anda tidak dapat mengubah peran akun sendiri.']);
            }

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $account->setRememberToken(Str::random(60));
            }

            $account->fill($data)->save();
        }, 3);

        return to_route('pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $request->validate(['is_active' => ['required', 'boolean']]);
        $active = $request->boolean('is_active');
        $actorId = $request->user()->getKey();

        DB::transaction(function () use ($actorId, $user, $active): void {
            $account = $this->lockAccountForManagement($actorId, $user);

            if (!$active && $actorId === $account->getKey()) {
                throw ValidationException::withMessages(['is_active' => 'Anda tidak dapat menonaktifkan akun sendiri.']);
            }

            $account->is_active = $active;
            if (!$active) {
                $account->setRememberToken(Str::random(60));
                if (config('session.driver') === 'database') {
                    DB::connection(config('session.connection'))->table(config('session.table'))
                        ->where('user_id', $account->getKey())->delete();
                }
            }
            $account->save();
        }, 3);

        return back()->with('success', $active ? 'Akun berhasil diaktifkan.' : 'Akun berhasil dinonaktifkan.');
    }

    private function lockAccountForManagement(int $actorId, User $user): User
    {
        $accounts = User::query()->whereKey([$actorId, $user->getKey()])
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

        abort_unless($accounts->get($actorId)?->can('manage-users'), 403);

        return $accounts->get($user->getKey());
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $email = Rule::unique('users', 'email');
        if ($user) {
            $email->ignore($user);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $email],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'page_permissions' => ['sometimes', 'array'],
            'page_permissions.*' => ['required', 'string', 'distinct', Rule::in(array_keys(User::PAGES))],
            'password' => [
                $user ? 'nullable' : 'required', 'bail', 'string', 'min:8', 'confirmed',
                function (string $attribute, string $value, Closure $fail): void {
                    if (strlen($value) > 72 || str_contains($value, "\0")) {
                        $fail('Kata sandi maksimal 72 byte dan tidak boleh berisi karakter null.');
                    }
                },
            ],
        ], [], [
            'name' => 'nama',
            'role' => 'peran',
            'password' => 'kata sandi',
        ]);

        if (!$user && !array_key_exists('page_permissions', $data)) {
            $data['page_permissions'] = [];
        }

        return $data;
    }
}
