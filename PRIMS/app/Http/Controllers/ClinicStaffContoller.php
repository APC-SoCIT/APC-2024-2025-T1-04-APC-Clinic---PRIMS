<?php

namespace App\Http\Controllers;

use App\Models\ClinicStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicStaffContoller extends Controller
{

    public function index()
    {
        $clinicstaffs = ClinicStaff::all();
        return view('clinicstaffs.index', compact('clinicstaffs'));
    }

    
    public function show($id)
    {
        $clinicstaff = ClinicStaff::findOrFail($id);

        // Access the user's email via relationship
        $userEmail = $clinicstaff->user->email;
        return view('clinicstaffs.show', compact('clinicstaff', 'userEmail'));
    }

}
