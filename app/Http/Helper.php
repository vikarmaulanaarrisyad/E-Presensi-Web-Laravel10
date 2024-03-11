<?php

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

if (!function_exists('upload')) {
    function upload($directory, $file, $filename = "")
    {
        $extensi  = $file->getClientOriginalExtension();
        $filename = "{$filename}_" . date('Ymdhis') . ".{$extensi}";

        Storage::disk('public')->putFileAs("/$directory", $file, $filename);

        return "$directory/$filename";
    }
}

if (!function_exists('format_uang')) {
    function format_uang($angka)
    {
        return number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('format_hari')) {
    function format_hari($hari)
    {
        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }

        return $hari_ini;
    }
}


if (!function_exists('tanggal_indonesia')) {
    function tanggal_indonesia($tgl, $tampil_hari = false, $tampil_jam = false)
    {
        $nama_hari  = array(
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
        );
        $nama_bulan = array(
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );

        $tahun   = substr($tgl, 0, 4);
        $bulan   = $nama_bulan[(int)substr($tgl, 5, 2)];
        $tanggal = substr($tgl, 8, 2);
        $text    = '';

        if ($tampil_hari) {
            $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
            $hari        = $nama_hari[$urutan_hari];
            $text       .= "$hari, $tanggal $bulan $tahun";
        } else {
            $text       .= "$tanggal $bulan $tahun";
        }

        if ($tampil_jam) {
            $jam   = date('H:i:s', strtotime($tgl)); // Get the time from the input date
            $text .= " $jam";
        }

        return $text;
    }
}

if (!function_exists('format_bulan')) {
    function format_bulan($bulan)
    {
        $nama_bulan = array(
            1 =>
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );

        return $nama_bulan[(int) $bulan];
    }
}

if (!function_exists('sembunyikan_text')) {
    function sembunyikan_text($words, $offset = 0)
    {
        $text = '';
        for ($i = 0; $i < strlen($words); $i++) {
            if (($i + $offset) >= strlen($words) && !($offset >= strlen($words))) {
                $text .= $words[$i];
            } else {
                $text .= '*';
            }
        }

        return $text;
    }
}

if (!function_exists('terbilang')) {
    function terbilang($angka)
    {
        $angka = abs($angka);
        $baca  = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
        $terbilang = '';

        if ($angka < 12) { // 0 - 11
            $terbilang = ' ' . $baca[$angka];
        } elseif ($angka < 20) { // 12 - 19
            $terbilang = terbilang($angka - 10) . ' belas';
        } elseif ($angka < 100) { // 20 - 99
            $terbilang = terbilang($angka / 10) . ' puluh' . terbilang($angka % 10);
        } elseif ($angka < 200) { // 100 - 199
            $terbilang = ' seratus' . terbilang($angka - 100);
        } elseif ($angka < 1000) { // 200 - 999
            $terbilang = terbilang($angka / 100) . ' ratus' . terbilang($angka % 100);
        } elseif ($angka < 2000) { // 1.000 - 1.999
            $terbilang = ' seribu' . terbilang($angka - 1000);
        } elseif ($angka < 1000000) { // 2.000 - 999.999
            $terbilang = terbilang($angka / 1000) . ' ribu' . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) { // 1000000 - 999.999.990
            $terbilang = terbilang($angka / 1000000) . ' juta' . terbilang($angka % 1000000);
        }

        return $terbilang;
    }
}

if (!function_exists('hitung_umur')) {
    function hitung_umur($tgl_lahir, $tahun = false, $bulan = false, $hari = false)
    {
        $birthDate = new DateTime($tgl_lahir);

        $today = new DateTime("today");

        if ($birthDate > $today) {
            exit("0 tahun 0 bulan 0 hari");
        }

        $y = $today->diff($birthDate)->y;
        $m = $today->diff($birthDate)->m;
        $d = $today->diff($birthDate)->d;

        // return $y . " tahun " . $m . " bulan " . $d . " hari";

        if (!$tahun) return $y;
        if ($bulan) return $m;
        if ($hari) return $d;
    }
}

if (!function_exists('hitung_hari')) {
    function hitung_hari($startDate, $endDate)
    {
        // Tanggal pertama
        $tanggal1 = Carbon::parse($startDate); // Ganti dengan tanggal dan waktu yang sesuai

        // Tanggal kedua
        $tanggal2 = Carbon::parse($endDate); // Ganti dengan tanggal dan waktu yang sesuai

        // Menghitung selisih waktu antara kedua tanggal
        $selisih = $tanggal1->diff($tanggal2);

        // Mendapatkan jumlah hari
        $jumlahHari = $selisih->days;

        // Mendapatkan jam, menit, dan detik
        $jam = $selisih->h;
        $menit = $selisih->i;
        $detik = $selisih->s;

        return $jumlahHari . " hari dari pelaksanaan.";
    }
}

function tambah_nol_didepan($value, $threshold = null)
{
    return sprintf("%0" . $threshold . "s", $value);
}
