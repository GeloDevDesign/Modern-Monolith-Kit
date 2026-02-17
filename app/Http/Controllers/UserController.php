<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        protected FilterService $filterService
    ) {}

    // In UserController.php

    public function index(Request $request)
    {
        $query = User::query()
            ->where('id', '!=', auth()->id())
            ->when($request->input('trashed'), function ($query, $trashed) {
                return $trashed === 'with' ? $query->withTrashed() : ($trashed === 'only' ? $query->onlyTrashed() : $query);
            })
            ->filter($request->only(['search', 'role', 'login_status', 'date_range']))
            ->sort(
                $request->input('sort_field', 'created_at'),
                $request->input('sort_order', -1)
            );

        $users = $this->paginateQuery($request, $query);

        return Inertia::render('Users/View', [
            'users' => $users,
            'roles' => $this->filterService->getRoles(),
            'loginStatuses' => $this->filterService->getLoginStatus(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Add', [
            'roles' => $this->filterService->getRoles(),
        ]);
    }

    public function show(User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => $this->filterService->getRoles(),
        ]);
    }

    public function store(UserRequest $request)
    {
        $validated = $request->safe()->except(['role', 'password', 'avatar']);

        $user = User::create([
            ...$validated,
            'type' => $request->validated('role'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $this->handleAvatarUpload($request, $user);

        return to_route('users.index')->with('success', 'User created successfully.');
    }

    public function update(UserRequest $request, User $user)
    {

        $validated = $request->safe()->except(['role', 'password', 'avatar']);

        $data = [
            ...$validated,
            'type' => $request->validated('role'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }

        $user->update($data);
        $this->handleAvatarUpload($request, $user);

        return redirect()->route('users.index')->withSuccess('User updated successfully.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return back()->with('success', 'User restored successfully.');

    }

    public function forceDelete($id)
    {

        $user = User::withTrashed()->findOrFail($id);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->forceDelete();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        return back()->with('success', 'User permanently deleted.');
    }

    public function destroy(User $user)
    {

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    private function handleAvatarUpload(Request $request, User $user): void
    {
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            $filename = "avatar-{$user->id}-".time().".{$file->extension()}";
            $path = $file->storeAs('avatars', $filename, 'public');

            $user->update(['avatar' => $path]);
        }
    }
}
