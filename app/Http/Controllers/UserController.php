<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Repositories\UserRepository;


class UserController extends Controller
{
    public function __construct(
        protected FilterService $filterService,
        protected UserRepository $userRepository
    ) {}

    // In UserController.php

    public function index(Request $request)
    {
        $query = $this->userRepository->where('id', '!=', auth()->id());

        return Inertia::render('Users/View', [
            'users' => $this->userRepository->paginate($request, $query),
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
        return Inertia::render('Users/Show', ['user' => $user]);
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
        $data = $request->validated();

        /** @var User $user */
        $user = $this->userRepository->create([
            ...$request->safe()->except(['role', 'password', 'avatar']),
            'type' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        $this->handleAvatarUpload($request, $user);

        return to_route('users.index')->with('success', 'User created successfully.');
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        $updateData = [
            ...$request->safe()->except(['role', 'password', 'avatar']),
            'type' => $data['role'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $this->userRepository->update($user->id, $updateData);
        $this->handleAvatarUpload($request, $user);

        return to_route('users.index')->with('success', 'User updated successfully.');
    }

    public function restore($id)
    {
        $this->userRepository->restore($id);
        return back()->with('success', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithTrashed($id);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $this->userRepository->forceDelete($id);
        return back()->with('success', 'User permanently deleted.');
    }

    public function destroy(User $user)
    {
        $this->userRepository->delete($user->id);
        return back()->with('success', 'User deleted successfully.');
    }

    private function handleAvatarUpload(Request $request, User $user): void
    {
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            $filename = "avatar-{$user->id}-" . time() . ".{$file->extension()}";
            $path = $file->storeAs('avatars', $filename, 'public');

            $this->userRepository->update($user->id, ['avatar' => $path]);
        }
    }
}
