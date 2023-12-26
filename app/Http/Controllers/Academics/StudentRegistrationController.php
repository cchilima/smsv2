<?php

namespace App\Http\Controllers\Academics;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Repositories\Academics\StudentRegistrationRepository;
use Illuminate\Http\Request;

class StudentRegistrationController extends Controller
{

    protected $registrationRepo;

    /**
     * Display a listing of the resource.
     */
    
    public function __construct(StudentRegistrationRepository $registrationRepo)
    {
       // $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
       // $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->registrationRepo = $registrationRepo;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = $this->registrationRepo->getAll();
        $academicInfo = $this->registrationRepo->getAcademicInfo();


        return view('pages.studentRegistration.index', compact('courses', 'academicInfo'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
