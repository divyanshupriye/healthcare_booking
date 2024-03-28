<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthcareProfessional;

class HealthcareProfessionalController extends Controller
{
    //
    public function index()
    {
        $healthcareProfessionals = HealthcareProfessional::all();
        return response()->json($healthcareProfessionals);
    }
}
