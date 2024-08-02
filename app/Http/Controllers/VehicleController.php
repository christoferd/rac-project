<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class VehicleController extends Controller
{
    function index()
    {
        if(userCan('access vehicles')) {
            return view('vehicle.index');
        }
        return view('app.unauthorized');
    }
}
