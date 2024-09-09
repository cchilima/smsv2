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
     * Store a newly created resource in storage.
     */
    public function store(SettingRequest $request)
    {
        try {
            $data = $request->only(['type', 'description']);
            $this->settingsRepo->create($data);

            return Qs::jsonStoreOk('Setting created successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to create setting: ' . $th->getMessage());
        }
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
        try {
            $data = $request->only(['description']);
            $this->settingsRepo->update($setting, $data);

            return Qs::jsonUpdateOk('Setting updated successfully');
        } catch (\Throwable $th) {
            return Qs::jsonError('Failed to update setting: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        return Qs::goBackWithError('Cannot delete system settings');

        // try {
        //     $this->settingsRepo->delete($setting);
        //     return Qs::goBackWithSuccess('Record deleted successfully');
        // } catch (\Throwable $th) {
        //     return Qs::goBackWithError('Failed to delete record: ' . $th->getMessage());
        // }
    }
}
