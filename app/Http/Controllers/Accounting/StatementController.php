<?php

namespace App\Http\Controllers\Accounting;

use App\Helpers\Qs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Accounting\StatementRepository;

class StatementController extends Controller
{

    protected $statementRepo;

    public function __construct(StatementRepository $statementRepo)
    {
        $this->statementRepo = $statementRepo;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        try {

            $collected = $this->statementRepo->collectPayment($request->amount, $request->academic_period, $request->student_id, $request->payment_method_id);

            if($collected){
                return Qs::jsonStoreOk();
            } else {
                dd($collected);
                return Qs::json('failed to collect payment', false);
            }
            

        } catch (\Exception $e) {

            // Log the error or handle it accordingly
            return Qs::json('failed to collect payment', false);
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