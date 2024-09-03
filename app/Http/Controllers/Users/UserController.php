<?php

namespace App\Http\Controllers\Users;

use DB;
use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Users\User;
use App\Http\Requests\Users\UserUpdate;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $dropdownData = $this->getDropdownData();
        $users = $this->userRepo->getAll();

        return view('pages.users.index', compact('users'), $dropdownData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dropdownData = $this->getDropdownData();
        return view('pages.users.create', $dropdownData);
    }

    /**
     * Get dropdown data for the create form.
     */
    private function getDropdownData()
    {
        $userTypeData = [
            'userTypes' => $this->userRepo->getuserTypes(),
        ];

        return $userTypeData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $request)
    {
        try {

            DB::beginTransaction();

            $userData = $request->only(['first_name', 'middle_name', 'last_name', 'gender', 'email', 'password', 'user_type_id']);
            $user = $this->userRepo->create($userData);

            DB::commit();

            return Qs::jsonStoreOk();
        } catch (\Exception $e) {

            DB::rollBack();
            // Log the error or handle it accordingly
            return Qs::jsonError('Failed to create record');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        // Returns dropdown options for user types
        $dropdownData = $this->getDropdownData();

        // Returns a user object
        $user = $this->userRepo->find($id);

        // Pass all relevant variables to the view
        return view('pages.users.edit', compact('user'), $dropdownData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $request, string $id)
    {
        try {

            DB::beginTransaction();

            $userData = $request->only(['first_name', 'middle_name', 'last_name', 'gender', 'email', 'user_type_id']);


            // Check if the user already exists
            $user = $this->userRepo->find($id);

            // Update the user data
            $user->update($userData);


            DB::commit();

            return Qs::jsonStoreOk();
        } catch (\Exception $e) {

            DB::rollBack();

            // Log the error or handle it accordingly
            return Qs::jsonError('Failed to create record');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userRepo->find($id)->delete();
        return Qs::goBackWithSuccess('Record deleted successfully');
    }
}
