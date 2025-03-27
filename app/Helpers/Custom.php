<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

if (!function_exists('log_r')) {
    function log_r($string = null, $var_dump = false)
    {
        ini_set('memory_limit', '-1');
        if ($var_dump) {
            var_dump($string);
        } else {
            echo "<pre>";
            print_r($string);
        }
        exit;
    }
}

if (!function_exists('dmy')) {
    function dmy($date)
    {
        return $date == null ? '-' : Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }
}

if (!function_exists('monthDmy')) {
    function monthDmy($date)
    {
        return $date == null ? '-' : Carbon::createFromFormat('Y-m-d H:i:s', $date)->translatedFormat('d F Y');
    }
}

if (!function_exists('fromIsoDateTime')) {
    function fromIsoDateTime($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}

if (!function_exists('monthDmy')) {
    function monthDmy($date)
    {
        return $date == null ? '-' : Carbon::createFromFormat('Y-m-d', $date)->translatedFormat('d F Y');
    }
}


if (!function_exists('setValueInput')) {
    function setValueInput($fieldName, $item)
    {
        if (old($fieldName)) {
            return old($fieldName);
        } else {
            if ($item) {
                return $item->$fieldName;
            }
        }
    }
}

if (!function_exists('setValueActive')) {
    function setValueActive($value)
    {
        // Nilai 'checked' ditambahkan jika $value == 1
        return $value == 1 ? 'checked' : '';
    }
}

if (!function_exists('sendJson')) {
    function sendJson($array)
    {
        header('Content-type: application/json');
        echo json_encode($array);
        die;
    }
}

function userLogin()
{
    return Auth::user();
}

function userLoginApi()
{
    return  auth('sanctum')->user();
}



function apiResponseSuccess($data, $message = '', $custom = [])
{ {
        $response = [
            'status' => 1,
            'data' => $data,
            'message' => $message
        ];
        if (!empty($custom)) {
            $response = array_merge($response, $custom);
        }

        return response()->json($response);
    }
}

function apiResponseError($message, $httpStatusCode = 200, $statusCode = 0)
{
    $response = [
        'status' => $statusCode,
        'data' => [],
        'message' => $message
    ];
    return response()->json($response, $httpStatusCode);
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('formatRibuan')) {
    function formatRibuan($angka)
    {
        return number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('encryptData')) {
    function encryptData($data)
    {
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', 'power', 0, $iv = 'jI9Ubj*kH7HbKj9w');
        return base64url_encode($iv . $encrypted);
    }
}

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data)
{
    return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
}

if (!function_exists('decryptData')) {
    function decryptData($encryptedData)
    {
        $data = base64url_decode($encryptedData);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'aes-256-cbc', 'power', 0, $iv);
    }
}


function uploadImage(Request $request, $field = '', $name = '', $dir_path = '', $validasi = [])
{
    if (!empty($validasi)) {
        $validator = Validator::make($request->all(), $validasi);
        if ($validator->fails()) {
            return apiResponseError($validator->errors()->first());
        }
    }
    $file = $request->file($field);
    if ($file) {
        $file_name = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $upload = $file->move(public_path($dir_path), $file_name);
        if (!$upload) {
            return apiResponseError("Gagal upload <b>$name</b>, Silahkan coba kembali!");
        }
        $path = "$dir_path/$file_name";
        $save = $path;
        return $path;
    } else {
        return apiResponseError("File <b>$name</b> tidak valid!");
    }
}

function uploadVideo(Request $request, $field = '', $name = '', $dir_path = '', $validasi = [])
{
    // Validasi input jika aturan validasi diberikan
    if (!empty($validasi)) {
        $validator = Validator::make($request->all(), $validasi);
        if ($validator->fails()) {
            return apiResponseError($validator->errors()->first());
        }
    }

    // Ambil file video dari request
    $file = $request->file($field);
    if ($file) {
        // Generate nama file unik
        $file_name = Str::random(20) . '.' . $file->getClientOriginalExtension();

        // Pastikan direktori tujuan ada
        if (!is_dir(public_path($dir_path))) {
            mkdir(public_path($dir_path), 0777, true);
        }

        // Upload file ke direktori tujuan
        $upload = $file->move(public_path($dir_path), $file_name);
        if (!$upload) {
            return apiResponseError("Gagal upload <b>$name</b>, silakan coba kembali!");
        }

        // Kembalikan path file yang berhasil diupload
        $path = "$dir_path/$file_name";
        return $path;
    } else {
        return apiResponseError("File <b>$name</b> tidak valid atau tidak ditemukan!");
    }
}

function removeFile($path)
{
    if (File::exists(public_path($path))) {
        File::delete($path);
    }
}



function cleanScript($postnya = '')
{
    if (empty($postnya)) {
        return $postnya;
    }
    // Mengonversi ampersand ke placeholder
    $postnya = str_replace('&', '[@AMPERSAND@]', $postnya);
    // Membersihkan input dari tag HTML dan mengonversi karakter khusus menjadi entitas HTML
    $postnya = htmlentities(strip_tags($postnya));
    // Mengembalikan placeholder ke ampersand
    $postnya = str_replace('[@AMPERSAND@]', '&', $postnya);
    return trim($postnya);
}




function hari_indo($tgl = '')
{
    // Konversi tanggal ke Day
    $hari = date('D', strtotime($tgl));
    // Array nama hari
    $nama_hari = array(
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'Kamis',
        'Fri' => "Jum'at",
        'Sat' => 'Sabtu'
    );
    // Jika $hari tidak sesuai dgn array
    if (empty($nama_hari[$hari])) {
        return 'Hari tidak valid!';
    }
    return $nama_hari[$hari]; //tampilkan hasil
}

function namaBulan()
{
    return [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
}
function bln_indo($tgl = '')
{
    // Konversi tanggal ke bulan
    $bln = (int)date('m', strtotime($tgl));
    $bln = $bln - 1;
    // Array nama bulan, kita coba buat lebih simpel
    $nama_bln = namaBulan();
    // Jika $bln tidak sesuai dgn array bulan
    if (empty($nama_bln[$bln])) {
        return 'Bulan tidak valid!';
    }
    return $nama_bln[$bln]; //tampilkan hasil
}

function tgl_now($aksi = '')
{
    date_default_timezone_set('Asia/Jakarta');
    if ($aksi == 'tgl') {
        $v = date('Y-m-d');
    } elseif ($aksi == 'jam') {
        $v = date('H:i:s');
    } elseif ($aksi == 'x') {
        $v = date('YmdHis');
    } else {
        $v = date('Y-m-d H:i:s');
    }
    return $v;
}

function waktu($tgl = '', $aksi = '')
{
    date_default_timezone_set('Asia/Jakarta');
    $tgl = ($tgl == '') ? tgl_now() : $tgl;
    $waktu_unix = strtotime($tgl);
    // Konversinya :D
    $harinya = hari_indo($tgl);
    $tglnya  = date('d', $waktu_unix);
    $blnnya  = bln_indo($tgl);
    $thnnya  = date('Y', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $jamnya   = date('H', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $menitnya = date('i', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $detiknya = date('s', $waktu_unix);

    // kita buat lebih simpel menggunakan array
    $arr = array(
        'hari'      => $harinya,
        'tgl'       => "$tglnya $blnnya $thnnya",
        'hari_tgl'  => "$harinya, $tglnya $blnnya $thnnya",
        'jam'       => $jamnya,
        'menit'     => $menitnya,
        'detik'     => $detiknya,
        'waktu'     => "$jamnya:$menitnya:$detiknya",
        'jam_menit' => "$jamnya:$menitnya",
        'hari_tgl_jam_menit' => $harinya . ", $tglnya $blnnya $thnnya $jamnya:$menitnya",
    );
    // Jika nama $aksi tidak ada dalam array maka tampilkan lengkapnya
    if (empty($arr[$aksi])) {
        return $harinya . ", $tglnya $blnnya $thnnya  $jamnya:$menitnya";
    }
    return $arr[$aksi]; //tampilkan hasil sesuai dgn $arr & $aksi
}

function date_indo($tgl = '', $aksi = '')
{
    $tgl = ($tgl == '') ? tgl_now() : $tgl;
    $waktu_unix = strtotime($tgl);
    // Konversinya :D
    $harinya = hari_indo($tgl);
    $tglnya  = date('d', $waktu_unix);
    $blnnya  = bln_indo($tgl);
    $thnnya  = date('Y', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $jamnya   = date('H', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $menitnya = date('i', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $detiknya = date('s', $waktu_unix);

    // kita buat lebih simpel menggunakan array
    $arr = array(
        'hari'      => $harinya,
        'tgl'       => "$tglnya $blnnya $thnnya",
        'hari_tgl'  => "$harinya, $tglnya $blnnya $thnnya",
        'jam'       => $jamnya,
        'menit'     => $menitnya,
        'detik'     => $detiknya,
        'waktu'     => "$jamnya:$menitnya:$detiknya",
        'jam_menit' => "$jamnya:$menitnya",
        'hari_tgl_jam_menit' => $harinya . ", $tglnya $blnnya $thnnya",
    );
    // Jika nama $aksi tidak ada dalam array maka tampilkan lengkapnya
    if (empty($arr[$aksi])) {
        return $harinya . ", $tglnya $blnnya $thnnya";
    }
    return $arr[$aksi]; //tampilkan hasil sesuai dgn $arr & $aksi
}
function time_indo($tgl = '', $aksi = '')
{
    $tgl = ($tgl == '') ? tgl_now() : $tgl;
    $waktu_unix = strtotime($tgl);
    // Konversinya :D
    $harinya = hari_indo($tgl);
    $tglnya  = date('d', $waktu_unix);
    $blnnya  = bln_indo($tgl);
    $thnnya  = date('Y', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $jamnya   = date('H', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $menitnya = date('i', $waktu_unix); // Perhatikan penggunaan waktu UNIX di sini
    $detiknya = date('s', $waktu_unix);

    // kita buat lebih simpel menggunakan array
    $arr = array(
        'hari'      => $harinya,
        'tgl'       => "$tglnya $blnnya $thnnya",
        'hari_tgl'  => "$harinya, $tglnya $blnnya $thnnya",
        'jam'       => $jamnya,
        'menit'     => $menitnya,
        'detik'     => $detiknya,
        'waktu'     => "$jamnya:$menitnya:$detiknya",
        'jam_menit' => "$jamnya:$menitnya",
        'hari_tgl_jam_menit' => "$jamnya:$menitnya",
    );
    // Jika nama $aksi tidak ada dalam array maka tampilkan lengkapnya
    if (empty($arr[$aksi])) {
        return "$jamnya:$menitnya";
    }
    return $arr[$aksi]; //tampilkan hasil sesuai dgn $arr & $aksi
}

function konversiFormatWaktu($waktu)
{
    // Buat objek DateTime dari data $waktu
    $waktuObj = new \DateTime($waktu);
    $waktuObj->setTimezone(new \DateTimeZone('Asia/Jakarta'));
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$waktuObj->format('w')];
    $tanggal = $waktuObj->format('j');
    $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$waktuObj->format('n') - 1];
    $tahun = $waktuObj->format('Y');
    $jam = $waktuObj->format('H');
    $menit = $waktuObj->format('i');
    return $hari . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun . ', ' . $jam . ':' . $menit;
}

function imageEmpty()
{
    return asset('/assets/img/power.png');
}


function userEmpty()
{
    return asset('/assets/img/customer/customer5.jpg');
}

function imageProductEmpty($res)
{
    return "https://ui-avatars.com/api/?name=" . $res;
}

function setImage($img = '', $aksi = '')
{
    $imgDefault = ($aksi == 'avatar') ? userEmpty() : imageEmpty();
    return (file_exists($img)) ? "/$img" : $imgDefault;
}

if (!function_exists('memberSince')) {
    function memberSince($date)
    {
        return $date == null ? '-' : Carbon::createFromFormat('Y-m-d H:i:s', $date)->translatedFormat('M Y');
    }
}