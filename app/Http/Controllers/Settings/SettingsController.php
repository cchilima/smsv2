<?php

namespace App\Http\Controllers\Settings;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Custom\SuperAdmin;
use App\Http\Middleware\Custom\TeamSA;
use App\Http\Requests\Settings\Setting as SettingRequest;
use App\Models\Settings\Setting;
use App\Repositories\Settings\SettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $settingsRepo;

    public function __construct(SettingsRepository $settingsRepo)
    {
        $this->middleware(TeamSA::class, ['except' => ['destroy',]]);
        $this->middleware(SuperAdmin::class, ['only' => ['destroy',]]);

        $this->settingsRepo = $settingsRepo;
    }

    public function index()
    {
        return view('pages.settings.index');
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
    public function store(SettingRequest $request)
    {
        $data = $request->only(['type', 'description']);
        $setting = $this->settingsRepo->create($data);

        if (!$setting) {
            return Qs::jsonError(__('msg.create_failed'));
        }

        return Qs::jsonStoreOk();
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        return view('pages.settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingRequest $request, Setting $setting)
    {
        $data = $request->only(['description']);
        $this->settingsRepo->update($setting, $data);
        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        $this->settingsRepo->delete($setting);
        return back()->with('flash_success', __('msg.delete_ok'));
    }
}
