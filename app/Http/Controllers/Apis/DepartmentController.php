<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\TeamSAT;
use App\Repositories\Academics\DepartmentsRepository;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departments;

    public function __construct(DepartmentsRepository $departments)
    {
        //$this->middleware(TeamSAT::class, ['except' => ['destroy',]]);
        $this->departments = $departments;
    }

    public function findBySlug($slug)
    {
        return response()->json($this->departments->findBySlug($slug));
    }
}
