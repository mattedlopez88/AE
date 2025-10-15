<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return Inertia::render('Admin/Users/index', [
            'users' => $users->map(fn ($user) => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'status' => $user->status,
            ]),
        ]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Log the error
            \Log::error("Error creating user: " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An unexpected error occurred.')
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function show(int $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        }

        return Inertia::render('Users/Show', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'document_id' => $user->document_id,
                'phone_number' => $user->phone_number,
                'status' => $user->status,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                // Include related data like lawyer/client profile if needed
                'lawyer_profile' => $user->lawyer ? $user->lawyer->only(['id', 'bar_number', 'firm_name']) : null,
                'client_profile' => $user->client ? $user->client->only(['id', 'case_reference']) : null,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(int $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found.');
        }

        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'document_id' => $user->document_id,
                'phone_number' => $user->phone_number,
                'status' => $user->status,
            ],
        ]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());

            if (!$user) {
                return redirect()->back()
                    ->with('error', 'User not found or could not be updated.')
                    ->withInput();
            }

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error("Error updating user (ID: {$id}): " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'An unexpected error occurred.')
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage (soft delete).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        try {
            if ($this->userService->deleteUser($id)) {
                return redirect()->route('users.index')
                    ->with('success', 'User deleted successfully.');
            }
            return redirect()->back()->with('error', 'User not found or could not be deleted.');
        } catch (\Exception $e) {
            \Log::error("Error deleting user (ID: {$id}): " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(int $id)
    {
        try {
            if ($this->userService->restoreUser($id)) {
                return redirect()->route('users.index')
                    ->with('success', 'User restored successfully.');
            }
            return redirect()->back()->with('error', 'User not found in trash or could not be restored.');
        } catch (\Exception $e) {
            \Log::error("Error restoring user (ID: {$id}): " . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'An unexpected error occurred.');
        }
    }
}
