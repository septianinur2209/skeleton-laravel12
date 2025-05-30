<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Dapatkan jalur pengalihan untuk pengguna yang tidak terotentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Merespons dengan JSON yang mengindikasikan status tidak terotentikasi
        return response()->json([
            'code' => 401,           // Kode status HTTP untuk tidak terotentikasi
            'message' => 'Unauthenticated', // Pesan untuk status tidak terotentikasi
            'status' => false,       // Status kesalahan sebagai false
            'result' => null         // Tidak ada hasil yang dikembalikan
        ], 401);                    // Kode status HTTP untuk respons
    }
}
