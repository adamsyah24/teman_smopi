<?php

namespace App\Http\Controllers;

use App\Helpers\WaBlast;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function submit(Request $request)
    {
        // Validasi data jika diperlukan
        $pengajar = $request->input('pengajar');
        $admin = User::whereIn('role_id', [1, 2, 4])
            ->where('id', $pengajar) // ← compare dengan ID_PENGAJAR
            ->first();

        $nomorWa = $admin ? [$admin->NOMOR_WA] : [];
        $tiket = (DB::table('t_laporan_admin')->max('TIKET') ?? 0) + 1;

        $id = Str::uuid()->toString();
        $namaPengadu = $request->input('nama');
        $nohp = $request->input('nohp');
        $asal_instansi = $request->input('asal_instansi');
        $nama_di = $request->input('nama_di');
        $nama_instansi = $request->input('nama_instansi');
        $nama_akun = $request->input('nama_akun');
        $jenis_akun = $request->input('jenis_akun');
        $menu_kendala = $request->input('menu_kendala');
        $deskripsi = $request->input('deskripsi');
        $bukti = $request->hasFile('bukti')
            ? $request->file('bukti')->store('bukti_pengaduan', 'public')
            : null;
        $numbers = [];

        $message = "📢 Laporan Baru!\n\n".
                    "Laporan baru telah masuk untuk diajukan dengan nomor tiket *$tiket*.\n".
                    'Terima kasih 🙏';

        if (! empty($nohp)) {
            $numbers[] = $nohp;
        }

        if (! empty($nomorWa)) {
            $numbers = array_merge($numbers, (array) $nomorWa);
        }

        // dd($pengajar, $nomorWa, $numbers);

        try {
            DB::transaction(function () use ($id, $tiket, $request, $pengajar, $bukti, $numbers, $message) {
                DB::table('t_laporan_admin')->insert([
                    'ID' => $id,
                    'TIKET' => $tiket,
                    'NAMA' => $request->nama,
                    'ID_KATEGORI' => $request->menu_kendala,
                    'ASAL_INSTANSI' => $request->asal_instansi,
                    'NAMA_DI' => $request->nama_di,
                    'NAMA_INSTANSI' => $request->nama_instansi,
                    'ID_PENGAJAR' => $pengajar,
                    'NAMA_AKUN' => $request->nama_akun,
                    'JENIS_AKUN' => $request->jenis_akun,
                    'DESKRIPSI' => $request->deskripsi,
                    'STATUS' => 1,
                    'BUKTI_SS' => $bukti,
                    'NO_HP' => $request->nohp,
                    'CREATED_AT' => now(),
                    'CREATED_BY' => 'system',
                    'UPDATED_AT' => now(),
                    'UPDATED_BY' => 'system',
                ]);
                // dd($message);

                WaBlast::send($numbers, $message);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'tiket' => $tiket,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function showForm()
    {
        $kategori = DB::table('ms_kategori')->get();
        $users = DB::table('users')
            ->where('role_id', '=', '2')
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($users);

        return view('form', compact('kategori', 'users'));
    }

    public function hapus(Request $request)
    {
        $id = $request->query('ID');

        $data = DB::table('t_laporan_admin')->where('ID', $id)->first();

        if (! $data) {
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
