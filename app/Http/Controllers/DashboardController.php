<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::where('user_id', auth()->user()->id)->first();
        
        return view('dashboard.dashboard',compact('karyawan'));
    }
}
