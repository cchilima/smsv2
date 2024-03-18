<?php

namespace App\Http\Controllers\Residency;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Residency\Province as ProvinceRequest;
use App\Models\Residency\Province;
use App\Repositories\Residency\CountryRepository;
use App\Repositories\Residency\ProvinceRepository;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class ProvinceController extends Controller
{
    protected $provinceRepo;
    protected $countryRepo;

    public function __construct(
        ProvinceRepository $provinceRepository,
        CountryRepository $countryRepository
    ) {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->provinceRepo = $provinceRepository;
        $this->countryRepo = $countryRepository;
    }

    public function index()
    {
        $provinces = $this->provinceRepo->getAll();
        $countries = $this->countryRepo->getAll();

        return view('pages.provinces.index', compact(['provinces', 'countries']));
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
    public function store(ProvinceRequest $request)
    {
        $data = $request->only(['name', 'country_id']);
        $province = $this->provinceRepo->create($data);

        if (!$province) {
            return Qs::jsonError(__('msg.create_failed'));
        }

        return Qs::jsonStoreOk();
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        $countries = $this->countryRepo->getAll();
        return view('pages.provinces.edit', compact(['province', 'countries']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProvinceRequest $request, Province $province)
    {
        $data = $request->only(['name', 'country_id']);
        $this->provinceRepo->update($province, $data);

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        try {
            $this->provinceRepo->delete($province);
            return back()->with('flash_success', __('msg.delete_ok'));
        } catch (\Throwable $th) {
            return back()->with('flash_error', __('msg.delete_error'));
        }
    }


    /**
     * Get the towns in a specified province from storage
     */
    public function getTownsByProvince(ProvinceRequest $request)
    {
        $provinceId = $request['provinceId'];
        $towns = $this->provinceRepo->getTownsByProvince($provinceId);

        return response()->json($towns);
    }
}
