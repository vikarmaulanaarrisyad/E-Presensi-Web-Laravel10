<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = date('Y-m-d');
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $userId = User::where('id', auth()->user()->id)->pluck('id');
        $karyawan = Karyawan::where('user_id', $userId)->first();
        $presensiHariIni = Presensi::whereDate('tgl_presensi', $hariIni)
            ->where('karyawan_id', $karyawan->id)
            ->first();
        $historiPresensiBulanIni = Presensi::whereMonth('tgl_presensi', $bulanIni)
            ->whereYear('tgl_presensi', $tahunIni)->orderBy('tgl_presensi')->get();

        return view('dashboard.dashboard', compact('karyawan', 'presensiHariIni', 'historiPresensiBulanIni'));
    }
}
