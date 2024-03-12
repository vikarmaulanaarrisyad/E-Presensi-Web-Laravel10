<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $karyawanId = $user->karyawan->id;
        $currentDate = Carbon::now()->format('Y-m-d');

        $cekPresensi = Presensi::where('karyawan_id', $karyawanId)->where('tgl_presensi', $currentDate)->count();

        $cekJamOut = Presensi::where('karyawan_id', $karyawanId)
            ->where('tgl_presensi', $currentDate)
            ->whereNull('jam_out')
            ->first();

        return view('presensi.create', compact('cekPresensi', 'cekJamOut'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'lokasi' => 'required',
            'image' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all(), 'message' => 'Data Tidak Valid'], 422);
        }

        $karyawan = Karyawan::where('user_id', Auth::user()->id)->first();
        $tglPresensi = date('Y-m-d');
        $jam = date('H:i:s');
        // -6.9138013,109.1344926
        $latitudeKantor = -6.9138013;
        $longitudeKantor = 109.1344926;
        $lokasi = $request->lokasi;
        $lokasiUser = explode(",", $lokasi);
        $latitudeUser = $lokasiUser[0];
        $longitudeUser = $lokasiUser[1];

        $cekPresensi = Presensi::where('karyawan_id', Auth::user()->karyawan->id)->where('tgl_presensi', date('Y-m-d'))->count();

        // Determine the folder path based on presensi type
        $folderType = ($cekPresensi > 0) ? 'out' : 'in';
        $folderPath = "public/uploads/absensi/$folderType/";

        $image = $request->image;
        $formatName = $karyawan->nik . "_$folderType" . "_" . $tglPresensi;
        $imageParts = explode(";base64", $image);
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        // Menghitung jarak radius kantor
        $jarak = $this->distance($latitudeKantor, $longitudeKantor, $latitudeUser, $longitudeUser);
        $radius = round($jarak);
        if ($radius > 20) {
            return response()->json(['message' => 'Maaf anda berada diluar radius, jarak anda ' . $radius . ' meter dari kantor!'], 422);
        }

        if ($cekPresensi > 0) {
            $absensi_pulang = [
                'jam_out' => $jam,
                'lokasi_out' => $lokasi,
                'foto_out' => $fileName,
            ];

            $update = Presensi::where('karyawan_id', Auth::user()->karyawan->id)->where('tgl_presensi', date('Y-m-d'))->update($absensi_pulang);

            if ($update) {
                Storage::put($file, $imageBase64);
                return response()->json(['message' => 'Data Berhasil disimpan'], 200);
            } else {
                return response()->json(['message' => 'Gagal menyimpan data'], 500);
            }
        } else {
            $absensi_masuk = [
                'karyawan_id' => $karyawan->id,
                'tgl_presensi' => $tglPresensi,
                'jam_in' => $jam,
                'lokasi_in' => $lokasi,
                'foto_in' => $fileName,
            ];

            $simpan = Presensi::create($absensi_masuk);

            if ($simpan) {
                Storage::put($file, $imageBase64);
                return response()->json(['message' => 'Data Berhasil disimpan'], 200);
            } else {
                return response()->json(['message' => 'Gagal menyimpan data'], 500);
            }
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    // Menghitung jarak radius
    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return $meters;
    }
}
