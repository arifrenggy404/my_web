<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Proyek;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class KontrolerProyek extends Controller
{
    public function ambilSemua(): JsonResponse
    {
        try {
            $daftarProyek = Proyek::all();
            
            return response()->json([
                'sukses' => true,
                'data' => $daftarProyek
            ], 200);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data proyek: ' . $e->getMessage());
            
            return response()->json([
                'sukses' => false,
                'pesan' => 'Terjadi kesalahan sistem internal.'
            ], 500);
        }
    }
}
