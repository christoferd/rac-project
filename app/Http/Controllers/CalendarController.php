<?php

namespace App\Http\Controllers;

use App\Models\RentalTask;
use Illuminate\Http\File;
use \Illuminate\Support\Facades\Storage;

class CalendarController extends Controller
{
    function index()
    {
        if(userCan('access calendar')) {
            return view('calendar.index');
        }
        return view('app.unauthorized');
    }

    function test()
    {
        if(userCan('access calendar')) {
            return view('calendar.test');
        }
        return view('app.unauthorized');
    }
}
