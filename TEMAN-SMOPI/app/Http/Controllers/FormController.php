<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function submit(Request $request)
    {
        // Validasi data jika diperlukan
        $tiket = DB::table('t_laporan_admin')->max('TIKET') + 1;

        $id = Str::uuid()->toString();
        $namaPengadu = $request->input('nama');
        $nohp = $request->input('nohp');
        $asal_instansi = $request->input('asal_instansi');
        $nama_di = $request->input('nama_di');
        $pengajar = $request->input('pengajar');
        $nama_akun = $request->input('nama_akun');
        $jenis_akun = $request->input('jenis_akun');
        $menu_kendala = $request->input('menu_kendala');
        $deskripsi = $request->input('deskripsi');
        $bukti = $request->file('bukti')->store('bukti_pengaduan', 'public');
        try {
            DB::insert('INSERT INTO t_laporan_admin (ID, TIKET, NAMA, ID_KATEGORI,
            ASAL_INSTANSI, NAMA_DI, ID_PENGAJAR, NAMA_AKUN, JENIS_AKUN, DESKRIPSI
            , STATUS, BUKTI_SS, NO_HP, CREATED_AT, CREATED_BY, UPDATED_AT, UPDATED_BY) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $tiket,
                $namaPengadu,
                $menu_kendala,
                $asal_instansi,
                $nama_di,
                $pengajar,
                $nama_akun,
                $jenis_akun,
                $deskripsi,
                1,
                $bukti,
                $nohp,
                now()->toDateString(),
                'system',
                now()->toDateString(),
                'system',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ]);
        }
    }


    public function showForm()
    {
        $kategori = DB::table('ms_kategori')->get();
        $users = DB::table('users')
            ->where('name', "!=", "SuperAdmin")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('form', compact('kategori', 'users'));
    }

    public function hapus(Request $request)
    {
        $id = $request->query('ID');

        $data = DB::table('t_laporan_admin')->where('ID', $id)->first();

        if (!$data) {
            abort(404, 'Data tidak ditemukan.');
        }

        DB::table('t_laporan_admin')
            ->where('ID', $id)
            ->update([
                'STATUS' => 0,
                'UPDATED_AT' => now(),
                'UPDATED_BY' => 'admin', // atau auth()->user()->name jika sudah login
            ]);

        return redirect()->back()->with('success', 'Data berhasil di-nonaktifkan.');
    }
}
