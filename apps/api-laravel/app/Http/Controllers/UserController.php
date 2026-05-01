<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function mapUser(User $user): array
    {
        $user->loadMissing('role');

        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'username'     => $user->username ?? null,
            'phone_number' => $user->phone_number ?? null,
            'avatar_url'   => $user->avatar_url ?? null,
            'bio'          => $user->bio ?? null,
            'role'         => $user->role?->name,
            'remember_me'  => (bool) $user->remember_me,
            'created_at'   => $user->created_at,
            'updated_at'   => $user->updated_at,
        ];
    }

    // GET /admin/users — hanya ADMIN
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->role?->name !== 'ADMIN') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $users = User::with('role')->latest()->get();

        return response()->json($users->map(fn ($u) => $this->mapUser($u)));
    }

    // POST /admin/users — hanya ADMIN
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->role?->name !== 'ADMIN') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:6'],
            'username'     => ['nullable', 'string', 'max:191', 'unique:users,username'],
            'phone_number' => ['nullable', 'string', 'max:32'],
            'avatar_url'   => ['nullable', 'string', 'max:500'],
            'role_name'    => ['nullable', Rule::in(['ADMIN', 'EDITOR', 'MEMBER'])],
        ]);

        $roleName = strtoupper($data['role_name'] ?? 'MEMBER');
        $role     = Role::where('name', $roleName)->firstOrFail();

        $user = User::create([
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'username'     => $data['username'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
            'avatar_url'   => $data['avatar_url'] ?? null,
            'role_id'      => $role->id,
        ]);

        return response()->json(['user' => $this->mapUser($user)], 201);
    }

    // PATCH /admin/users/{user} — hanya ADMIN
    public function update(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role?->name !== 'ADMIN') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'email'        => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'     => ['sometimes', 'nullable', 'string', 'min:6'],
            'username'     => ['sometimes', 'nullable', 'string', 'max:191'],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:32'],
            'avatar_url'   => ['sometimes', 'nullable', 'string', 'max:500'],
            'role_name'    => ['sometimes', Rule::in(['ADMIN', 'EDITOR', 'MEMBER'])],
        ]);

        if (isset($data['role_name'])) {
            $role = Role::where('name', strtoupper($data['role_name']))->firstOrFail();
            $user->role_id = $role->id;
            unset($data['role_name']);
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill($data)->save();

        return response()->json(['user' => $this->mapUser($user->fresh('role'))]);
    }

    // DELETE /admin/users/{user} — hanya ADMIN
    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role?->name !== 'ADMIN') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'Kamu tidak bisa menghapus akun sendiri.'], 400);
        }

        $user->delete();

        return response()->json(['ok' => true]);
    }

    // GET /users/me — user sendiri
    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $this->mapUser($request->user())]);
    }

    // PUT /users/me — update profil sendiri
    public function updateMe(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'         => ['sometimes', 'string', 'max:191'],
            'email'        => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'     => ['sometimes', 'nullable', 'string', 'min:6'],
            'username'     => ['sometimes', 'nullable', 'string', 'max:191', Rule::unique('users', 'username')->ignore($user->id)],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:32'],
            'avatar_url'   => ['sometimes', 'nullable', 'string', 'max:500'],
            'bio'          => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->fill($data)->save();

        return response()->json(['user' => $this->mapUser($user->fresh('role'))]);
    }
}
