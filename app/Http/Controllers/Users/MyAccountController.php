<?php

namespace App\Http\Controllers\Users;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Users\UserChangePass;
use App\Repositories\Users\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyAccountController extends Controller
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
        $d = Auth::user();
        $data = $this->userRepo->find($d->id);
        return view('pages.users.my_account', compact('data'));
    }

    public function change_pass(UserChangePass $req)
    {
        try {
            $user_id = Auth::user()->id;
            $my_pass = Auth::user()->password;
            $old_pass = $req->current_password;
            $new_pass = $req->password;

            if (password_verify($old_pass, $my_pass)) {
                $data['password'] = Hash::make($new_pass);
                $this->userRepo->update($user_id, $data);

                return Qs::goBackWithSuccess('Password changed successfully');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to change password');
        }
    }
}
