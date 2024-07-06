<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function executeCommands()
    {        
        // Execute the desired commands using Artisan and exec
        Artisan::call('migrate');
        Artisan::call('db:seed');
        Artisan::call('optimize:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');
        Artisan::call('queue:restart');

        return response()->json(['message' => 'Commands executed successfully']);
    }
}
