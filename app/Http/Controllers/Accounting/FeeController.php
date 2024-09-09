<?php

namespace App\Http\Controllers\Accounting;


use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Fees\Fee;
use App\Http\Requests\Fees\FeeUpdate;
use App\Repositories\Accounting\FeeRepository;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FeeController extends Controller
{

    protected $fees;

    public function __construct(FeeRepository $fees)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->fees = $fees;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Fee $request)
    {
        try {
            $data = $request->only(['name', 'type']);
            $this->fees->create($data);

            return Qs::jsonStoreOk('Fee created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create fee: ' . $th->getMessage());
        }
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
        try {
            $data = $request->only(['name']);
            $this->fees->update($id, $data);

            return Qs::jsonUpdateOk('Fee updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update fee: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->fees->find($id)->delete();
            return Qs::goBackWithSuccess('Fee deleted successfully');
        } catch (QueryException $qe) {
            if ($qe->errorInfo[1] == 1451) {
                return Qs::goBackWithError('Cannot delete fee referenced by other records');
            }
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete fee: ' . $th->getMessage());
        }
    }
}
