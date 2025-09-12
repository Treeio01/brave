<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getDownloadLinks()
    {
        $windows = Setting::getValue('download_links_windows', '');
        $mac = Setting::getValue('download_links_mac', '');

        return response()->json([
            'windows' => $windows,
            'mac' => $mac
        ]);
    }
}