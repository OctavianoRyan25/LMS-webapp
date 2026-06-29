<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('role')
            ->when($request->search, fn($q, $s) =>
                $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")
            )
            ->when($request->role, fn($q, $r) =>
                $q->where('role_id', $r)
            )
            ->latest()
            ->paginate(15);

        $roles = Role::all();

        $stats = [
            'total'  => User::count(),
            'active' => User::whereNotNull('email_verified_at')->count(),
        ];

        return view('page.users.index', [
            'activeNav' => 'users',
            'users'     => $users,
            'roles'     => $roles,
            'stats'     => $stats,
        ]);
    }

    public function create(): View
    {
        return view('page.users.create', [
            'activeNav' => 'users',
            'roles'     => Role::all(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function show(User $user): View
    {
        $user->load('role');

        return view('page.users.show', [
            'activeNav' => 'users',
            'user'      => $user,
        ]);
    }

    public function edit(User $user): View
    {
        return view('page.users.create', [
            'activeNav' => 'users',
            'user'      => $user,
            'roles'     => Role::all(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Jika password kosong, jangan update
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
