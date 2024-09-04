<?php

namespace App\Http\Controllers\Residency;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Residency\Town as TownRequest;
use App\Models\Residency\Town;
use App\Repositories\Residency\CountryRepository;
use App\Repositories\Residency\ProvinceRepository;
use App\Repositories\Residency\TownRepository;

class TownController extends Controller
{
    protected $countryRepo;
    protected $provinceRepo;
    protected $townRepo;

    public function __construct(
        TownRepository $townRepository,
        CountryRepository $countryRepository,
        ProvinceRepository $provinceRepository
    ) {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->countryRepo = $countryRepository;
        $this->provinceRepo = $provinceRepository;
        $this->townRepo = $townRepository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TownRequest $request)
    {
        try {
            $data = $request->only(['name', 'country_id', 'province_id']);
            $this->townRepo->create($data);

            return Qs::jsonStoreOk('Town created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create town: ' . $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Town $town)
    {
        $countries = $this->countryRepo->getAll();

        return view('pages.towns.edit', compact(['countries', 'town']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TownRequest $request, Town $town)
    {
        try {
            $data = $request->only(['name', 'country_id', 'province_id']);
            $this->townRepo->update($town, $data);

            return Qs::jsonUpdateOk('Town updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update town: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Town $town)
    {
        try {
            $this->townRepo->delete($town);
            return Qs::goBackWithSuccess('Record deleted successfully');
        } catch (\Throwable $th) {
            return Qs::goBackWithError('Failed to delete record');
        }
    }
}
