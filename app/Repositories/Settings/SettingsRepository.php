<?php

namespace App\Repositories\Settings;

use App\Models\Settings\Setting;

class SettingsRepository
{

    public function find($id)
    {
        return Setting::find($id);
    }

    public function getAll()
    {
        return Setting::all();
    }

    public function create($data)
    {
        return Setting::create($data);
    }

    public function update($setting, $data)
    {
        return $setting->update($data);
    }

    public function delete($setting)
    {
        return $setting->delete();
    }
}
