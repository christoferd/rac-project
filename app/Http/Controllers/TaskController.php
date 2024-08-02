<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    function index()
    {
        if(userCan('access tasks')) {
            return view('task.index');
        }
        return view('app.unauthorized');
    }
}
