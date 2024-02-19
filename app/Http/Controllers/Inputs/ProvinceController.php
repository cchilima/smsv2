<?php

namespace App\Http\Controllers\Inputs;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Inputs\Province as ProvinceRequest;
use App\Repositories\Inputs\ProvinceRepository;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    protected $provinceRepo;

    public function __construct(ProvinceRepository $provinceRepository)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->provinceRepo = $provinceRepository;
    }

    public function getTownsByProvince(ProvinceRequest $request)
    {
        $provinceId = $request['provinceId'];
        $towns = $this->provinceRepo->getTownsByProvince($provinceId);

        return response()->json($towns);
    }
}
