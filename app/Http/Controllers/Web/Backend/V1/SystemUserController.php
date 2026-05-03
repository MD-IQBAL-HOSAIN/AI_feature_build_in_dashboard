<?php
namespace App\Http\Controllers\Web\Backend\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSystemUserRequest;
use App\Http\Requests\UpdateSystemUserRequest;
use App\Interfaces\SystemUserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class SystemUserController extends Controller
{
    public function __construct(private readonly SystemUserServiceInterface $systemUserService)
    {
    }

    /**
     * Display the system user listing page or return DataTable JSON for AJAX requests.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->systemUserService->getAllLatestQuery();

            return DataTables::eloquent($users)
                ->addIndexColumn()
                ->addColumn('avatar', function ($user) {
                    $avatar = $user->avatar ? asset($user->avatar) : asset('frontend/default-avatar-profile.jpg');

                    return '<img src="' . $avatar . '" alt="avatar" width="40" height="40" style="object-fit: cover; border-radius: 50%;">';
                })
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('email', function ($user) {
                    return $user->email;
                })
                ->addColumn('role', function ($user) {
                    $role = strtolower((string) $user->role);
                    $label = ucfirst($role ?: 'N/A');

                    return '<span class="role-pill role-' . e($role) . '">' . e($label) . '</span>';
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor  = $data->status ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status ? '26px' : '2px';

                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })
                ->addColumn('action', function ($data) {
                    return '
                <button onclick="edit(' . $data->id . ')" type="button" class="btn btn-info btn-sm">
                    <i class="mdi mdi-pencil"></i>
                </button>
                <button type="button" onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger btn-sm del">
                    <i class="mdi mdi-delete"></i>
                </button>
            ';
                })
                ->rawColumns(['avatar', 'role', 'status', 'action'])
                ->make(true);
        }
        return view('backend.v1.users.system_users.index');
    }

    /**
     * Show the create form with available role options.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roles = $this->systemUserService->getRoleOptions();

        return view('backend.v1.users.system_users.form', compact('roles'));
    }

    /**
     * Store a newly created system user.
     *
     * @param StoreSystemUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSystemUserRequest $request)
    {
        try {
            $this->systemUserService->create(
                $request->validated(),
                $request->file('avatar')
            );

            return redirect()->route('system-user.index')->with('t-success', 'System User Successfully created');
        } catch (Throwable $e) {
            return redirect()->route('system-user.index')->with('t-error', 'System User Failed to Create ...' . $e->getMessage());
        }
    }

    /**
     * Show the edit form for the selected system user.
     *
     * @param User $system_user
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $system_user)
    {
        $roles = $this->systemUserService->getRoleOptions();

        return view('backend.v1.users.system_users.form', compact('system_user', 'roles'));
    }

    /**
     * Update the selected system user.
     *
     * @param UpdateSystemUserRequest $request
     * @param User $system_user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSystemUserRequest $request, User $system_user)
    {
        try {
            $this->systemUserService->update(
                $system_user,
                $request->validated(),
                $request->file('avatar')
            );

        } catch (Throwable $e) {
            return redirect()->route('system-user.index')->with('t-error', 'System User Failed to Update ...' . $e->getMessage());
        }
        return redirect()->route('system-user.index')->with('t-success', 'System User Successfully updated');
    }

    /**
     * Toggle status for a system user.
     *
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id)
    {
        try {
            $this->systemUserService->toggleStatus($id);

            return response()->json([
                'success' => true,
                'status' => 't-success',
                'message' => 'Status Changed Successfully',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 't-error',
                'message' => 'Status Change Failed ...' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete the selected system user.
     *
     * @param User $system_user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $system_user)
    {
        try {
            $authUserId = (int) Auth::id();
            $this->systemUserService->delete($system_user, $authUserId);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 't-error',
                'message' => 'User delete Failed ...' . $e->getMessage(),
            ]);
        }
        return response()->json([
            'success' => true,
            'status' => 't-success',
            'message' => 'User deleted Successfully',
        ]);
    }
}








