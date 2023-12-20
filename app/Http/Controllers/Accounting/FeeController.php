<?php

namespace App\Http\Controllers\Accounting;


use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Fees\Fee;
use App\Http\Requests\Fees\FeeUpdate;
use App\Repositories\Accounting\FeeRepository;
use Illuminate\Http\Request;

class FeeController extends Controller
{

    protected $fees;

    public function __construct(FeeRepository $fees)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',] ]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',] ]);

        $this->fees = $fees;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fees['fees'] = $this->fees->getAll();
        return view('pages.fees.index',$fees);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.fees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Fee $request)
    {
        $data = $request->only(['name']);
        $data['chart_of_account_id'] = 'data';


        $fee = $this->fees->create($data);

        if ($fee) {
            return Qs::jsonStoreOk();
        } else {
            return Qs::json(false,'msg.create_failed');
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
        $fee = $this->fees->find($id);

        return !is_null($fee) ? view('pages.fees.edit', compact('fee'))
            : Qs::goWithDanger('pages.fees.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->only(['name']);
        $this->fees->update($id, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->fees->find($id)->delete();
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
