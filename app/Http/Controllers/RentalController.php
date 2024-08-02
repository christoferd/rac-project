<?php

namespace App\Http\Controllers;

class RentalController extends Controller
{
    public function index()
    {
        if(userCan('access rentals')) {
            return view('rental.index');
        }
        return view('app.unauthorized');
    }
}
